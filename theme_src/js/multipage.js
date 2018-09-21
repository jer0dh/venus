
//From: https://stackoverflow.com/questions/4481485/changing-css-pseudo-element-styles-via-javascript#

document.addEventListener('DOMContentLoaded', function() {
    const addRule = (function (style) {
        var sheet = document.head.appendChild(style).sheet;
        return function (selector, css) {
            var propText = typeof css === "string" ? css : Object.keys(css).map(function (p) {
                return p + ":" + (p === "content" ? "'" + css[p] + "'" : css[p]);
            }).join(";");
            sheet.insertRule(selector + "{" + propText + "}", sheet.cssRules.length);
        };
    })(document.createElement("style"));


    addRule("p:before", {
        display: "block",
        width: "100px",
        height: "100px",
        background: "red",
        "border-radius": "50%",
        content: "''"
    });

});