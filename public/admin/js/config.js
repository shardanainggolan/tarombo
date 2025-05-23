"use strict";
window.config = {
    colors: {
        primary: "#7367f0",
        secondary: "#a8aaae",
        success: "#28c76f",
        info: "#00cfe8",
        warning: "#ff9f43",
        danger: "#ea5455",
        dark: "#4b4b4b",
        black: "#000",
        white: "#fff",
        cardColor: "#fff",
        bodyBg: "#f8f7fa",
        bodyColor: "#6f6b7d",
        headingColor: "#5d596c",
        textMuted: "#a5a3ae",
        borderColor: "#dbdade"
    },
    colors_label: {
        primary: "#7367f029",
        secondary: "#a8aaae29",
        success: "#28c76f29",
        info: "#00cfe829",
        warning: "#ff9f4329",
        danger: "#ea545529",
        dark: "#4b4b4b29"
    },
    colors_dark: {
        cardColor: "#2f3349",
        bodyBg: "#25293c",
        bodyColor: "#b6bee3",
        headingColor: "#cfd3ec",
        textMuted: "#7983bb",
        borderColor: "#434968"
    },
    enableMenuLocalStorage: !0
}, window.assetsPath = document.documentElement.getAttribute("data-assets-path"), window.templateName = document.documentElement.getAttribute("data-template"), window.rtlSupport = !0, "undefined" != typeof TemplateCustomizer && (window.templateCustomizer = new TemplateCustomizer({
    cssPath: assetsPath,
    themesPath: assetsPath,
    displayCustomizer: !0,
    lang: localStorage.getItem("templateCustomizer-" + templateName + "--Lang") || "en",
    controls: ["rtl", "style", "headerType", "contentLayout", "layoutCollapsed", "layoutNavbarOptions", "themes"]
}));
