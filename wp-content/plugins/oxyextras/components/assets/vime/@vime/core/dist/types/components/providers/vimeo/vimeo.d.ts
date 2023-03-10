import { EventEmitter } from '../../../stencil-public-runtime';
import { MediaProvider } from '../MediaProvider';
import { Logger } from '../../core/player/PlayerLogger';
export declare class Vimeo implements MediaProvider<HTMLVimeEmbedElement> {
  private embed;
  private dispatch;
  private initialMuted;
  private fetchVideoInfo?;
  private pendingDurationCall?;
  private pendingMediaTitleCall?;
  private defaultInternalState;
  private volume;
  private hasLoaded;
  private pendingPlayRequest?;
  private internalState;
  embedSrc: string;
  mediaTitle: string;
  /**
   * The Vimeo resource ID of the video to load.
   */
  videoId: string;
  onVideoIdChange(): void;
  /**
   * Whether to display the video owner's name.
   */
  byline: boolean;
  /**
   * The hexadecimal color value of the playback controls. The embed settings of the video
   * might override this value.
   */
  color?: string;
  /**
   * Whether to display the video owner's portrait.
   */
  portrait: boolean;
  /**
   * Turns off automatically determining the aspect ratio of the current video.
   */
  noAutoAspectRatio: boolean;
  /**
   * The absolute URL of a custom poster to be used for the current video.
   */
  poster?: string;
  onCustomPosterChange(): void;
  /**
   * @internal
   */
  language: string;
  /**
   * @internal
   */
  aspectRatio: string;
  /**
   * @internal
   */
  autoplay: boolean;
  /**
   * @internal
   */
  controls: boolean;
  /**
   * @internal
   */
  logger?: Logger;
  /**
   * @internal
   */
  loop: boolean;
  /**
   * @internal
   */
  muted: boolean;
  /**
   * @internal
   */
  playsinline: boolean;
  /**
   * @internal
   */
  vLoadStart: EventEmitter<void>;
  constructor();
  connectedCallback(): void;
  disconnectedCallback(): void;
  private getOrigin;
  private getPreconnections;
  private remoteControl;
  private buildParams;
  private getVideoInfo;
  private onTimeChange;
  private timeRAF?;
  private cancelTimeUpdates;
  private requestTimeUpdates;
  private onSeeked;
  private onVimeoMethod;
  private onLoaded;
  private onVimeoEvent;
  private onEmbedSrcChange;
  private onEmbedMessage;
  private adjustPosition;
  /**
   * @internal
   */
  getAdapter(): Promise<{
    getInternalPlayer: () => Promise<HTMLVimeEmbedElement>;
    play: () => Promise<void>;
    pause: () => Promise<void>;
    canPlay: (type: any) => Promise<boolean>;
    setCurrentTime: (time: number) => Promise<void>;
    setMuted: (muted: boolean) => Promise<void>;
    setVolume: (volume: number) => Promise<void>;
    canSetPlaybackRate: () => Promise<boolean>;
    setPlaybackRate: (rate: number) => Promise<void>;
  }>;
  render(): any;
}
