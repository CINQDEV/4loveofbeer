jQuery(document).ready(function ($) {
    $("body").on(
        "DOMSubtreeModified",
        ".oxygen-sidebar-top",
        function () {
            const filteredTabElement = document.getElementsByClassName('oxygen-sidebar-tabs-tab oxy-styles-present oxy-styles-present-filtered');

            if (filteredTabElement.length === 0) {

                const presentTabElements = document.getElementsByClassName('oxygen-sidebar-tabs-tab oxy-styles-present');
                if (presentTabElements.length > 0) {

                    const newTabElement = presentTabElements[0].cloneNode(true);
                    newTabElement.classList.add('oxy-styles-present-filtered');
                    newTabElement.innerHTML = 'Filtered'
                    presentTabElements[0].parentNode.appendChild(newTabElement)

                    newTabElement.addEventListener('click', function() {
                        presentTabElements[0].click();

                        setTimeout((() => {
                            const sidebarElements = document.getElementsByClassName('oxygen-sidebar-advanced-home');
                            if (sidebarElements.length) {
                                sidebarElements[0].classList.add('oxygen-sidebar-advanced-home-filtered')
                            }

                            const tabElements = document.getElementsByClassName('oxygen-sidebar-tabs-tab');
                            for (const tabElement of tabElements) {
                                tabElement.classList.remove('oxygen-sidebar-tabs-tab-active')

                                if (!tabElement.classList.contains('oxy-styles-present-filtered')) {
                                    tabElement.addEventListener('click', function () {
                                        if (tabElement.classList.contains('oxy-styles-present')) {
                                            presentTabElements[0].classList.add('oxygen-sidebar-tabs-tab-active')
                                        }

                                        newTabElement.classList.remove('oxygen-sidebar-tabs-tab-active');

                                        if (sidebarElements.length) {
                                            sidebarElements[0].classList.remove('oxygen-sidebar-advanced-home-filtered')
                                        }
                                    })
                                }
                            }
                            newTabElement.classList.add('oxygen-sidebar-tabs-tab-active');

                        }),50)

                    }, false);
                }
            }
        }
    );
})

