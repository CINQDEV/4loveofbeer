import { h, Component, Method, Prop, State, Watch, Event, } from '@stencil/core';
import { withProviderConnect, withProviderContext } from '../MediaProvider';
import { decodeJSON, loadImage } from '../../../utils/network';
import { isString, isNumber, isArray, isBoolean, isObject, } from '../../../utils/unit';
import { mapYouTubePlaybackQuality } from './YouTubePlaybackQuality';
import { MediaType } from '../../core/player/MediaType';
import { ViewType } from '../../core/player/ViewType';
import { createProviderDispatcher } from '../ProviderDispatcher';
const posterCache = new Map();
export class YouTube {
  constructor() {
    this.defaultInternalState = {};
    this.hasCued = false;
    this.internalState = {
      paused: true,
      duration: 0,
      seeking: false,
      playbackStarted: false,
      currentTime: 0,
      lastTimeUpdate: 0,
      playbackRate: 1,
      state: -1,
    };
    this.embedSrc = '';
    this.mediaTitle = '';
    /**
     * Whether cookies should be enabled on the embed.
     */
    this.cookies = false;
    /**
     * Whether the fullscreen control should be shown.
     */
    this.showFullscreenControl = true;
    /**
     * @internal
     */
    this.language = 'en';
    /**
     * @internal
     */
    this.autoplay = false;
    /**
     * @internal
     */
    this.controls = false;
    /**
     * @internal
     */
    this.loop = false;
    /**
     * @internal
     */
    this.muted = false;
    /**
     * @internal
     */
    this.playsinline = false;
    withProviderConnect(this);
    withProviderContext(this);
  }
  onVideoIdChange() {
    this.embedSrc = `${this.getOrigin()}/embed/${this.videoId}`;
    this.fetchPosterURL = this.findPosterURL();
  }
  onCustomPosterChange() {
    this.dispatch('currentPoster', this.poster);
  }
  connectedCallback() {
    this.dispatch = createProviderDispatcher(this);
    this.dispatch('viewType', ViewType.Video);
    this.onVideoIdChange();
    this.initialMuted = this.muted;
    this.defaultInternalState = Object.assign({}, this.internalState);
  }
  /**
   * @internal
   */
  async getAdapter() {
    const canPlayRegex = /(?:youtu\.be|youtube|youtube\.com|youtube-nocookie\.com)\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|)((?:\w|-){11})/;
    return {
      getInternalPlayer: async () => this.embed,
      play: async () => { this.remoteControl("playVideo" /* Play */); },
      pause: async () => { this.remoteControl("pauseVideo" /* Pause */); },
      canPlay: async (type) => isString(type) && canPlayRegex.test(type),
      setCurrentTime: async (time) => { this.remoteControl("seekTo" /* Seek */, time); },
      setMuted: async (muted) => {
        muted ? this.remoteControl("mute" /* Mute */) : this.remoteControl("unMute" /* Unmute */);
      },
      setVolume: async (volume) => {
        this.remoteControl("setVolume" /* SetVolume */, volume);
      },
      canSetPlaybackRate: async () => true,
      setPlaybackRate: async (rate) => {
        this.remoteControl("setPlaybackRate" /* SetPlaybackRate */, rate);
      },
    };
  }
  getOrigin() {
    return !this.cookies ? 'https://www.youtube-nocookie.com' : 'https://www.youtube.com';
  }
  getPreconnections() {
    return [
      this.getOrigin(),
      'https://www.google.com',
      'https://googleads.g.doubleclick.net',
      'https://static.doubleclick.net',
      'https://s.ytimg.com',
      'https://i.ytimg.com',
    ];
  }
  remoteControl(command, arg) {
    return this.embed.postMessage({
      event: 'command',
      func: command,
      args: arg ? [arg] : undefined,
    });
  }
  buildParams() {
    return {
      enablejsapi: 1,
      cc_lang_pref: this.language,
      hl: this.language,
      widget_referrer: window.location.href,
      fs: this.showFullscreenControl ? 1 : 0,
      controls: this.controls ? 1 : 0,
      disablekb: !this.controls ? 1 : 0,
      iv_load_policy: this.controls ? 1 : 3,
      mute: this.initialMuted ? 1 : 0,
      playsinline: this.playsinline ? 1 : 0,
      autoplay: this.autoplay ? 1 : 0,
    };
  }
  onEmbedSrcChange() {
    this.hasCued = false;
    this.vLoadStart.emit();
  }
  onEmbedLoaded() {
    // Seems like we have to wait a random small delay or else YT player isn't ready.
    window.setTimeout(() => this.embed.postMessage({ event: 'listening' }), 100);
  }
  async findPosterURL() {
    if (posterCache.has(this.videoId))
      return posterCache.get(this.videoId);
    const posterURL = (quality) => `https://i.ytimg.com/vi/${this.videoId}/${quality}.jpg`;
    /**
     * We are testing a that the image has a min-width of 121px because if the thumbnail does not
     * exist YouTube returns a blank/error image that is 120px wide.
     */
    return loadImage(posterURL('maxresdefault'), 121) // 1080p (no padding)
      .catch(() => loadImage(posterURL('sddefault'), 121)) // 640p (padded 4:3)
      .catch(() => loadImage(posterURL('hqdefault'), 121)) // 480p (padded 4:3)
      .then((img) => {
      const poster = img.src;
      posterCache.set(this.videoId, poster);
      return poster;
    });
  }
  onCued() {
    if (this.hasCued)
      return;
    this.internalState = Object.assign({}, this.defaultInternalState);
    this.dispatch('currentSrc', this.embedSrc);
    this.dispatch('mediaType', MediaType.Video);
    this.fetchPosterURL.then((poster) => {
      var _a;
      this.dispatch('currentPoster', (_a = this.poster) !== null && _a !== void 0 ? _a : poster);
      this.dispatch('playbackReady', true);
      // Re-attempt play.
      if (this.autoplay)
        this.remoteControl("playVideo" /* Play */);
    });
    this.hasCued = true;
  }
  onPlayerStateChange(state) {
    if (state === -1 /* Unstarted */)
      return;
    const isPlaying = (state === 1 /* Playing */);
    const isBuffering = (state === 3 /* Buffering */);
    this.dispatch('buffering', isBuffering);
    // Attempt to detect `play` events early.
    if (this.internalState.paused && (isBuffering || isPlaying)) {
      this.internalState.paused = false;
      this.dispatch('paused', false);
      if (!this.internalState.playbackStarted) {
        this.dispatch('playbackStarted', true);
        this.internalState.playbackStarted = true;
      }
    }
    switch (state) {
      case 5 /* Cued */:
        this.onCued();
        break;
      case 1 /* Playing */:
        // Incase of autoplay which might skip `Cued` event.
        this.onCued();
        this.dispatch('playing', true);
        break;
      case 2 /* Paused */:
        this.internalState.paused = true;
        this.dispatch('paused', true);
        break;
      case 0 /* Ended */:
        if (this.loop) {
          window.setTimeout(() => { this.remoteControl("playVideo" /* Play */); }, 150);
        }
        else {
          this.dispatch('playbackEnded', true);
          this.internalState.paused = true;
          this.dispatch('paused', true);
        }
        break;
    }
    this.internalState.state = state;
  }
  calcCurrentTime(time) {
    let currentTime = time;
    if (this.internalState.state === 0 /* Ended */) {
      return this.internalState.duration;
    }
    if (this.internalState.state === 1 /* Playing */) {
      const elapsedTime = (Date.now() / 1E3 - this.defaultInternalState.lastTimeUpdate) * this.internalState.playbackRate;
      if (elapsedTime > 0)
        currentTime += Math.min(elapsedTime, 1);
    }
    return currentTime;
  }
  onTimeChange(time) {
    const currentTime = this.calcCurrentTime(time);
    this.dispatch('currentTime', currentTime);
    // This is the only way to detect `seeking`.
    if (Math.abs(this.internalState.currentTime - currentTime) > 1.5) {
      this.internalState.seeking = true;
      this.dispatch('seeking', true);
    }
    this.internalState.currentTime = currentTime;
  }
  onBufferedChange(buffered) {
    this.dispatch('buffered', buffered);
    /**
     * This is the only way to detect `seeked`. Unfortunately while the player is `paused` `seeking`
     * and `seeked` will fire at the same time, there are no updates inbetween -_-. We need an
     * artifical delay between the two events.
     */
    if (this.internalState.seeking && (buffered > this.internalState.currentTime)) {
      window.setTimeout(() => {
        this.internalState.seeking = false;
        this.dispatch('seeking', false);
      }, this.internalState.paused ? 100 : 0);
    }
  }
  onEmbedMessage(event) {
    const message = event.detail;
    const { info } = message;
    if (!info)
      return;
    if (isObject(info.videoData))
      this.dispatch('mediaTitle', info.videoData.title);
    if (isNumber(info.duration)) {
      this.internalState.duration = info.duration;
      this.dispatch('duration', info.duration);
    }
    if (isArray(info.availablePlaybackRates)) {
      this.dispatch('playbackRates', info.availablePlaybackRates);
    }
    if (isNumber(info.playbackRate)) {
      this.internalState.playbackRate = info.playbackRate;
      this.dispatch('playbackRate', info.playbackRate);
    }
    if (isNumber(info.currentTime))
      this.onTimeChange(info.currentTime);
    if (isNumber(info.currentTimeLastUpdated)) {
      this.internalState.lastTimeUpdate = info.currentTimeLastUpdated;
    }
    if (isNumber(info.videoLoadedFraction)) {
      this.onBufferedChange(info.videoLoadedFraction * this.internalState.duration);
    }
    if (isNumber(info.volume))
      this.dispatch('volume', info.volume);
    if (isBoolean(info.muted))
      this.dispatch('muted', info.muted);
    if (isArray(info.availableQualityLevels)) {
      this.dispatch('playbackQualities', info.availableQualityLevels.map((q) => mapYouTubePlaybackQuality(q)));
    }
    if (isString(info.playbackQuality)) {
      this.dispatch('playbackQuality', mapYouTubePlaybackQuality(info.playbackQuality));
    }
    if (isNumber(info.playerState))
      this.onPlayerStateChange(info.playerState);
  }
  render() {
    return (h("vime-embed", { embedSrc: this.embedSrc, mediaTitle: this.mediaTitle, origin: this.getOrigin(), params: this.buildParams(), decoder: decodeJSON, preconnections: this.getPreconnections(), onVEmbedLoaded: this.onEmbedLoaded.bind(this), onVEmbedMessage: this.onEmbedMessage.bind(this), onVEmbedSrcChange: this.onEmbedSrcChange.bind(this), ref: (el) => { this.embed = el; } }));
  }
  static get is() { return "vime-youtube"; }
  static get properties() { return {
    "cookies": {
      "type": "boolean",
      "mutable": false,
      "complexType": {
        "original": "boolean",
        "resolved": "boolean",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": "Whether cookies should be enabled on the embed."
      },
      "attribute": "cookies",
      "reflect": false,
      "defaultValue": "false"
    },
    "videoId": {
      "type": "string",
      "mutable": false,
      "complexType": {
        "original": "string",
        "resolved": "string",
        "references": {}
      },
      "required": true,
      "optional": false,
      "docs": {
        "tags": [],
        "text": "The YouTube resource ID of the video to load."
      },
      "attribute": "video-id",
      "reflect": false
    },
    "showFullscreenControl": {
      "type": "boolean",
      "mutable": false,
      "complexType": {
        "original": "boolean",
        "resolved": "boolean",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": "Whether the fullscreen control should be shown."
      },
      "attribute": "show-fullscreen-control",
      "reflect": false,
      "defaultValue": "true"
    },
    "poster": {
      "type": "string",
      "mutable": false,
      "complexType": {
        "original": "string",
        "resolved": "string | undefined",
        "references": {}
      },
      "required": false,
      "optional": true,
      "docs": {
        "tags": [],
        "text": "The absolute URL of a custom poster to be used for the current video."
      },
      "attribute": "poster",
      "reflect": false
    },
    "language": {
      "type": "string",
      "mutable": false,
      "complexType": {
        "original": "string",
        "resolved": "string",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [{
            "text": undefined,
            "name": "internal"
          }],
        "text": ""
      },
      "attribute": "language",
      "reflect": false,
      "defaultValue": "'en'"
    },
    "autoplay": {
      "type": "boolean",
      "mutable": false,
      "complexType": {
        "original": "boolean",
        "resolved": "boolean",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [{
            "text": undefined,
            "name": "internal"
          }],
        "text": ""
      },
      "attribute": "autoplay",
      "reflect": false,
      "defaultValue": "false"
    },
    "controls": {
      "type": "boolean",
      "mutable": false,
      "complexType": {
        "original": "boolean",
        "resolved": "boolean",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [{
            "text": undefined,
            "name": "internal"
          }],
        "text": ""
      },
      "attribute": "controls",
      "reflect": false,
      "defaultValue": "false"
    },
    "logger": {
      "type": "unknown",
      "mutable": false,
      "complexType": {
        "original": "Logger",
        "resolved": "Logger | undefined",
        "references": {
          "Logger": {
            "location": "import",
            "path": "../../core/player/PlayerLogger"
          }
        }
      },
      "required": false,
      "optional": true,
      "docs": {
        "tags": [{
            "text": undefined,
            "name": "internal"
          }],
        "text": ""
      }
    },
    "loop": {
      "type": "boolean",
      "mutable": false,
      "complexType": {
        "original": "boolean",
        "resolved": "boolean",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [{
            "text": undefined,
            "name": "internal"
          }],
        "text": ""
      },
      "attribute": "loop",
      "reflect": false,
      "defaultValue": "false"
    },
    "muted": {
      "type": "boolean",
      "mutable": false,
      "complexType": {
        "original": "boolean",
        "resolved": "boolean",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [{
            "text": undefined,
            "name": "internal"
          }],
        "text": ""
      },
      "attribute": "muted",
      "reflect": false,
      "defaultValue": "false"
    },
    "playsinline": {
      "type": "boolean",
      "mutable": false,
      "complexType": {
        "original": "boolean",
        "resolved": "boolean",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [{
            "text": undefined,
            "name": "internal"
          }],
        "text": ""
      },
      "attribute": "playsinline",
      "reflect": false,
      "defaultValue": "false"
    }
  }; }
  static get states() { return {
    "embedSrc": {},
    "mediaTitle": {}
  }; }
  static get events() { return [{
      "method": "vLoadStart",
      "name": "vLoadStart",
      "bubbles": true,
      "cancelable": true,
      "composed": true,
      "docs": {
        "tags": [{
            "text": undefined,
            "name": "internal"
          }],
        "text": ""
      },
      "complexType": {
        "original": "void",
        "resolved": "void",
        "references": {}
      }
    }]; }
  static get methods() { return {
    "getAdapter": {
      "complexType": {
        "signature": "() => Promise<{ getInternalPlayer: () => Promise<HTMLVimeEmbedElement>; play: () => Promise<void>; pause: () => Promise<void>; canPlay: (type: any) => Promise<boolean>; setCurrentTime: (time: number) => Promise<void>; setMuted: (muted: boolean) => Promise<void>; setVolume: (volume: number) => Promise<void>; canSetPlaybackRate: () => Promise<boolean>; setPlaybackRate: (rate: number) => Promise<void>; }>",
        "parameters": [],
        "references": {
          "Promise": {
            "location": "global"
          },
          "HTMLVimeEmbedElement": {
            "location": "global"
          }
        },
        "return": "Promise<{ getInternalPlayer: () => Promise<HTMLVimeEmbedElement>; play: () => Promise<void>; pause: () => Promise<void>; canPlay: (type: any) => Promise<boolean>; setCurrentTime: (time: number) => Promise<void>; setMuted: (muted: boolean) => Promise<void>; setVolume: (volume: number) => Promise<void>; canSetPlaybackRate: () => Promise<boolean>; setPlaybackRate: (rate: number) => Promise<void>; }>"
      },
      "docs": {
        "text": "",
        "tags": [{
            "name": "internal",
            "text": undefined
          }]
      }
    }
  }; }
  static get watchers() { return [{
      "propName": "cookies",
      "methodName": "onVideoIdChange"
    }, {
      "propName": "videoId",
      "methodName": "onVideoIdChange"
    }, {
      "propName": "poster",
      "methodName": "onCustomPosterChange"
    }]; }
}
