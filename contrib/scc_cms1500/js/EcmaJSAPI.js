/*
 * ===========================================
 * Java Pdf Extraction Decoding Access Library
 * ===========================================
 *
 * Project Info: http://www.idrsolutions.com
 * Help section for developers at http://www.idrsolutions.com/java-pdf-library-support/
 *
 * (C) Copyright 1997-2015, IDRsolutions and Contributors.
 *
 * This file is part of JPedal
 *
 @LICENSE@
 *
 * ---------------
 * EcmaJSAPI.js
 * ---------------
 */
//ADBC is static use

//var XFACreatorTool = "JPDF2HTML5";

app = new App();


function updateFDFXFA() {
    var doc = new Doc();
    app.activeDocs[0] = doc;

    var dd = document.getElementById("FDFXFA_FormType");
    if (dd) {
        app.isFDF = dd.textContent === "FDF";
    }
    dd = document.getElementById("FDFXFA_Processing");
    if(dd){
        dd.style.display = "none";
    }
}

window.onscroll = function (e) {
    var yy = document.documentElement.scrollTop || document.body.scrollTop;
    var hh = document.documentElement.scrollHeight || document.body.scrollHeight;
    var totalPages = document.getElementsByClassName("pageArea").length;
    app.activeDocs[0].pageNum = Math.max(Math.min(parseInt(yy / hh * totalPages), totalPages), 0);
    dd = document.getElementById("FDFXFA_PageLabel");
    if (dd) {
        dd.textContent = app.activeDocs[0].pageNum + 1;
    }
};

function Page() {

}

var ADBC = {
//sql types
    SQLT_BIGINT: 0, SQLT_BINARY: 1, SQLT_BIT: 2, SQLT_CHAR: 3, SQLT_DATE: 4,
    SQLT_DECIMAL: 5, SQLT_DOUBLE: 6, SQLT_FLOAT: 7, SQLT_INTEGER: 8,
    SQLT_LONGVARBINARY: 9, SQLT_LONGVARCHAR: 10, SQLT_NUMERIC: 11, SQLT_REAL: 12,
    SQLT_SMALLINT: 13, SQLT_TIME: 14, SQLT_TIMESTAMP: 15, SQLT_TINYINT: 16,
    SQLT_VARBINARY: 17, SQLT_VARCHAR: 18, SQLT_NCHAR: 19, SQLT_NVARCHAR: 20,
    SQLT_NTEXT: 21,
//javascript types
    Numeric: 0, String: 1, Binary: 2, Boolean: 3, Time: 4, Date: 5, TimeStamp: 6,
    getDataSourceList: function () {
        console.log("ADBC.getDataSourceList() not defined");
        return new Array();
    },
    newConnnection: function () {
        var obj = {};
        if (arguments[0] instanceof Object) {
            obj = arguments[0];
        } else {
            obj.cDSN = arguments[0];
            switch (arguments.length) {
                case 2:
                    obj.cUID = arguments[1];
                    break;
                case 3:
                    obj.cUID = arguments[1];
                    obj.cPWD = arguments[2];
                    break;
            }
        }
        console.log("ADBC.newConnection not defined");
        return null;
    }
};


function Alerter() {
}

Object.defineProperty(Alerter.prototype, "dispatch", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("dispatch not defined");
        return true;
    }
});


function Alert() {
    this.type = "";
    this.doc = null;
    this.fromUser = false;
    this.error = {message: ""};
    this.errorText = "";
    this.fileName = "";
    this.selection = null; //mediaselection object
}

function AlternatePresentation() {
    this.active = false;
    this.type = "";
}

Object.defineProperty(AlternatePresentation.prototype, "start", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("start not defined");
        return true;
    }
});

Object.defineProperty(AlternatePresentation.prototype, "stop", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("stop not defined");
        return true;
    }
});


var AnnotationType = {
    Caret: "Caret", Circle: "Circle", FileAttachment: "FileAttachment",
    FreeText: "FreeText", Highlight: "Highlight", Ink: "Ink", Link: "Link", Line: "Line",
    Polygon: "Polygon", PolyLine: "PolyLine", Sound: "Sound", Square: "Square",
    Squiggly: "Squiggly", Stamp: "Stamp", StrikeOut: "StrikeOut", Text: "Text",
    Underline: "Underline"
};

function Annotation() {

    this.type = "Text"; //required
    this.rect = [];//required
    this.page = 0; //required and pages starting from 0 used by IDR

    this.alignment = 0; //left0, center3, right2
    //Approved,AsIs,Confidential,Departmental,Draft,Experimental,Expired,Final,
    //ForComment,ForPublicRelease,NotApproved,NotForPublicRelease,Sold,TopSecret
    this.AP = "Approved";
    //none,OpenArrow,ClosedArrow,ROpenArrow,RCloseArrow,Butt,Diamond,Circle,Square
    //Slash
    this.arrowBegin = "None";
    this.arrowEnd = "None"; //same as arrowbegin
    this.attachIcon = "PushPin"; //PaperClip,PushPin,Graph,Tag
    this.author = "";
    this.borderEffectIntensity = "";
    this.callout = [];
    this.caretSymbol = ""; //"P" or "S";
    this.contents = "";
    this.creationDate = new Date();
    this.dash = [];
    this.delay = false;
    this.doc = null; //doc object;
    this.doCaption = false;
    this.fillColor = [];// Ex : for gray [0.7] for rgb [0.2,0.7,1.0] for cmyk [1,0,0,0.2];
    this.gestures = [];
    this.hidden = false;
    this.inReplyTo = "";
    this.intent = "";
    this.leaderExtend = 0;
    this.leaderLength = 0;
    this.lineEnding = "none"; //same as arrowbegin
    this.lock = false;
    this.modDate = new Date();
    this.name = "";
    //Check,Circle,Comment,Cross,Help,Insert,Key,NewParagraph,Note,RightArrow,
    //RightPointer,Star,UpArrow,UpLeftArrow
    this.noteIcon = "Note";
    this.noView = false;
    this.opacity = 1.0;
    this.open = false;
    this.point = [];
    this.points = [];
    this.popupOpen = true;
    this.popupRect = [];
    this.print = false;
    this.quads = [];
    this.readOnly = false;
    this.refType = "";
    this.richContents = []; //array of span objects page69
    this.richDefaults = null; //span object;
    this.rotate = 0;
    this.seqNum = 0;
    this.soundIcon = "";
    this.state = "";
    this.stateModel = "";
    this.strokeColor = [];
    this.style = "";
    this.subject = "";
    this.textFont = "";
    this.textSize = 10;
    this.toggleNoView = false;
    this.vertices = null; //array of arrays;
    this.width = 1;

    //below are custom addition for link
    this.URI = "";
    this.GoTo = "";

}

function RichContent() {
    this.text = "";
    this.textColor = "#000000";
    this.textSize = 10;
}

Object.defineProperty(Annotation.prototype, "getDictionaryString", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        var str = "<</Type /Annot /Subtype /" + this.type + " /Rect [ ";
        for (var i = 0, ii = this.rect.length; i < ii; i++) {
            str += this.rect[i] + " ";
        }
        str += "]";
        if (this.type === AnnotationType.Highlight) {
            str += "/QuadPoints [ ";
            for (var i = 0, ii = this.quads.length; i < ii; i++) {
                str += (this.quads[i] + " ");
            }
            str += "]";
        } else if (this.type === AnnotationType.Text) {
            if (this.contents.length > 0) {
                str += "/Contents (" + this.contents + ")";
            }
            if (this.open) {
                str += "/Open true";
            }
        } else if (this.type === AnnotationType.Link) {
            if (this.URI.length > 0) {
                str += "/A <</Type /Action /S /URI /URI (" + this.URI + ")>>";
            } else if (this.GoTo.length > 0) {
                str += "/Dest [" + this.GoTo + " /Fit]>>";
            }
            if (this.quads.length > 0) {
                str += "/QuadPoints [ ";
                for (var i = 0, ii = this.quads.length; i < ii; i++) {
                    str += (this.quads[i] + " ");
                }
                str += "]";
            }
        } else if (this.type === AnnotationType.Line) { //stroke color is important to view line
            str += ("/L [" + this.points[0] + " " + this.points[1] + " " + this.points[2] + " " + this.points[3] + "]");
        } else if (this.type === AnnotationType.Polygon || this.type === AnnotationType.PolyLine) {
            str += "/Vertices [";
            for (var i = 0, ii = this.vertices.length; i < ii; i++) {
                str += this.vertices[i] + " ";
            }
            str += "]";
        } else if (this.type === AnnotationType.Ink) {
            str += "/InkList [";
            var gs = this.gestures;
            for (var i = 0, ii = gs.length; i < ii; i++) {
                var cp = gs[i];
                str += " [";
                for (var j = 0, jj = cp.length; j < jj; j++) {
                    str += cp[j] + " ";
                }
                str += "] ";
            }
            str += "]";
        } else if (this.type === AnnotationType.FreeText) {
            var contentStr = "";
            for (var i = 0, ii = this.richContents.length; i < ii; i++) {
                var rc = this.richContents[i];
                contentStr += "<span style='font-size:" + rc.textSize + ";color:"
                        + rc.textColor + "'>" + rc.text + "</span>";
            }
            str += "/DA (/Arial " + this.textSize + " Tf)"
                    + "/RC (<body><p>" + contentStr + "</p></body>)";
        }

        if (this.type === AnnotationType.Line
                || this.type === AnnotationType.Highlight
                || this.type === AnnotationType.FreeText
                || this.type === AnnotationType.Link
                || this.type === AnnotationType.Square
                || this.type === AnnotationType.Circle
                || this.type === AnnotationType.Polygon
                || this.type === AnnotationType.PolyLine
                || this.type === AnnotationType.Ink) {
            if (this.opacity < 1.0) {
                str += "/CA " + this.opacity;
            }
            if (this.width !== 1) {
                str += "/BS <</Type /Border /W " + this.width + ">>";
            }
            if (this.fillColor.length > 0) {
                str += "/IC [";
                for (var i = 0, ii = this.fillColor.length; i < ii; i++) {
                    str += this.fillColor[i] + " ";
                }
                str += "]";
            }
            if (this.strokeColor.length > 0) {
                str += "/C [";
                for (var i = 0, ii = this.strokeColor.length; i < ii; i++) {
                    str += this.strokeColor[i] + " ";
                }
                str += "]";
            }
        }
        str += ">>";
        console.log(str);

        return str;
    }
});

Object.defineProperty(Annotation.prototype, "destroy", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("destroy not defined");
        return true;
    }
});

Object.defineProperty(Annotation.prototype, "getProps", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getProps not defined");
        return true;
    }
});

Object.defineProperty(Annotation.prototype, "getStateInModel", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getStateInModel not defined");
        return true;
    }
});

Object.defineProperty(Annotation.prototype, "setProps", {
    writable: true, enumerable: true, configurable: true,
    value: function (objParam) {
        for (var nn in objParam) {
            if (nn in this) {
                this[nn] = objParam[nn];
            }
        }
        return true;
    }
});

Object.defineProperty(Annotation.prototype, "transitionToState", {
    writable: true, enumerable: true, configurable: true,
    value: function (objParam) {
        console.log("transitionToState not defined");
    }
});

function Annot3D() {
    this.activated = false;
    this.context3D = null;
    this.innerRect = new Array();
    this.name = "";
    this.page = 1;
    this.rect = new Array();
}

//app object heavily used in XFA
function App() {
    this.isFDF = false;

    this.activeDocs = [];
    this.calculate = false;
    this.contstants = null;
    this.focusRect = false;
    this.formsVersion = 6.0;
    this.fromPDFConverters = new Array();
    this.fs = new FullScreen();
    this.fullScreen = false;
    this.language = "ENU";
    this.media = new Media();
    this.monitors = {};
    this.numPlugins = 0;
    this.openInPlace = false;
    this.platform = "WIN";
    this.plugins = new Array();
    this.printColorProfiles = new Array();
    this.printNames = new Array();
    this.runtimeHighlight = false;
    this.runtimeHightlightColor = new Array();
    this.thermometer = null;
    this.toolBar = false;
    this.toolBarHorizontal = false;
    this.toolBarVertical = false;
    this.viewerType = "Exchange-Pro";
    this.viewerVariation = "Full";
    this.viewerVersion = 6.0;
}
;

Object.defineProperty(App.prototype, "addMenuItem", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("addMenuItem not defined");
    }
});

Object.defineProperty(App.prototype, "addSubMenu", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("addSubMenu not defined");
    }
});

Object.defineProperty(App.prototype, "addToolButton", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("addToolButton not defined");
    }
});

Object.defineProperty(App.prototype, "alert", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        var obj = {nIcon: 0, nType: 0, cTitle: "Adobe Acrobat", oDoc: null, oCheckBox: null};
        console.log("alert not defined");
    }
});


Object.defineProperty(App.prototype, "beep", {
    writable: true, enumerable: true, configurable: true,
    value: function (nType) {
        console.log("beep not defined");
    }
});


Object.defineProperty(App.prototype, "beginPriv", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("beginPriv not defined");
    }
});

Object.defineProperty(App.prototype, "browseForDoc", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("browseForDoc not defined");
    }
});

Object.defineProperty(App.prototype, "clearInterval", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("clearInterval not defined");
    }
});

Object.defineProperty(App.prototype, "clearTimeOut", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("clearTimeOut not defined");
    }
});

Object.defineProperty(App.prototype, "endPriv", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("endPriv not defined");
    }
});

//read page 106 for dialog box handling as it is major subject to be implemented
Object.defineProperty(App.prototype, "execDialog", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("execDialog not defined");
    }
});

Object.defineProperty(App.prototype, "execMenuItem", {
    writable: true, enumerable: true, configurable: true,
    value: function (str) {

        var lbl = document.getElementById("FDFXFA_PageLabel");

        var pageAreas = document.getElementsByClassName("pageArea");
        var totalPages = pageAreas.length;

        var sParam = str.toUpperCase();
        if (sParam === "SAVEAS") {
            if (this.isFDF) {
                var pdfName = document.getElementById("FDFXFA_PDFName").textContent;
                EcmaParser.saveFormToPDF(pdfName);
            } else {
                createXFAPDF();
            }
        } else if (sParam === "PRINT") {
            window.print();
        } else if (sParam === "FIRSTPAGE") {
            this.activeDocs[0].pageNum = 0;
            window.scrollTo(0, 0);
            if (lbl) {
                lbl.textContent = 1;
            }
        } else if (sParam === "PREVPAGE") {
            if (this.activeDocs[0].pageNum > 0) {
                this.activeDocs[0].pageNum--;
                if (lbl) {
                    lbl.textContent = this.activeDocs[0].pageNum + 1;
                }
                var yy = pageAreas[this.activeDocs[0].pageNum].offsetTop;
                window.scrollTo(0, yy);
            }
        } else if (sParam === "NEXTPAGE") {
            if (this.activeDocs[0].pageNum < (totalPages - 1)) {
                this.activeDocs[0].pageNum++;
                if (lbl) {
                    lbl.textContent = this.activeDocs[0].pageNum + 1;
                }
                var yy = pageAreas[this.activeDocs[0].pageNum].offsetTop;
                window.scrollTo(0, yy);

            }
        } else if (sParam === "LASTPAGE") {
            this.activeDocs[0].pageNum = totalPages - 1;
            if (lbl) {
                lbl.textContent = this.activeDocs[0].pageNum + 1;
            }
            var yy = pageAreas[this.activeDocs[0].pageNum].offsetTop;
            window.scrollTo(0, yy);
        }
    }
});

Object.defineProperty(App.prototype, "getNthPlugInName", {
    writable: true, enumerable: true, configurable: true,
    value: function (nIndex) {
        console.log("getNthPluginName not defined");
    }
});

Object.defineProperty(App.prototype, "getPath", {
    writable: true, enumerable: true, configurable: true,
    value: function (cCategory, cFolder) {
        console.log("getPath not defined");
    }
});

Object.defineProperty(App.prototype, "goBack", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("goBack not defined");
    }
});

Object.defineProperty(App.prototype, "goForward", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("goForward not defined");
    }
});


Object.defineProperty(App.prototype, "hideMenuItem", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("hideMenuItem not defined");
    }
});

Object.defineProperty(App.prototype, "hideToolbarButton", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("hideToolbarButton not defined");
    }
});

Object.defineProperty(App.prototype, "launchURL", {
    writable: true, enumerable: true, configurable: true,
    value: function (cURL, bNewFrame) {
        console.log("launchURL not defined");
    }
});

Object.defineProperty(App.prototype, "listMenuItems", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("listMenuItems not defined");
        return [];
    }
});

Object.defineProperty(App.prototype, "listToolbarButtons", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("listToolbarButtons not defined");
    }
});

Object.defineProperty(App.prototype, "mailGetAddrs", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("mailGetAddrs not defined");
    }
});

Object.defineProperty(App.prototype, "mailMsg", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("mailMsg not defined");
    }
});

Object.defineProperty(App.prototype, "mailGetAddrs", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("mailGetAddrs not defined");
    }
});

Object.defineProperty(App.prototype, "newDoc", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("newDoc not defined");
    }
});


Object.defineProperty(App.prototype, "newFDF", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("newFDF not defined");
    }
});

Object.defineProperty(App.prototype, "openDoc", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("openDoc not defined");
    }
});


Object.defineProperty(App.prototype, "openFDF", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("openFDF not defined");
    }
});

Object.defineProperty(App.prototype, "popUpMenu", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("popUpMenu not defined");
    }
});


Object.defineProperty(App.prototype, "popUpMenuEx", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("popUpMenuEx not defined");
    }
});

Object.defineProperty(App.prototype, "removeToolButton", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("removeToolButton not defined");
    }
});

Object.defineProperty(App.prototype, "response", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("response not defined");
    }
});

Object.defineProperty(App.prototype, "setInterval", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("setInterval not defined");
    }
});

Object.defineProperty(App.prototype, "setTimeOut", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("setTimeOut not defined");
    }
});

Object.defineProperty(App.prototype, "trustedFunction", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("trustedFunction not defined");
    }
});


Object.defineProperty(App.prototype, "trustPropagatorFunction", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("trustPropagatorFunction not defined");
    }
});

function Media() {
    this.align = {
        topLeft: 0, topCenter: 1, topRight: 2, centerLeft: 3, center: 4,
        centerRight: 5, bottomLeft: 6, bottomCenter: 7, bottomRight: 8
    };
    this.canResize = {
        no: 0, keepRatio: 1, yes: 2
    };
    this.closeReason = {
        general: 0, error: 1, done: 2, stop: 3, play: 4, uiGeneral: 5,
        uiScreen: 6, uiEdit: 7, docClose: 8, docSave: 9, docChange: 10
    };
    this.defaultVisible = true;
    this.ifOffScreen = {
        allow: 0, forseOnScreen: 1, cancel: 2
    };
    this.layout = {
        meet: 0, slice: 1, fill: 2, scroll: 3, hidden: 4, standard: 5
    };
    this.monitorType = {
        document: 0, nonDocument: 1, primary: 3, bestColor: 4, largest: 5,
        tallest: 6, widest: 7
    };
    this.openCode = {
        success: 0, failGeneral: 1, failSecurityWindow: 2, failPlayerMixed: 3,
        failPlayerSecurityPrompt: 4, failPlayerNotFound: 5, failPlayerMimeType: 6,
        failPlayerSecurity: 7, failPlayerData: 8
    };
    this.over = {
        pageWindow: 0, appWindow: 1, desktop: 2, monitor: 3
    };
    this.pageEventNames = {
        Open: 0, Close: 1, InView: 2, OutView: 3
    };
    this.raiseCode = {
        fileNotFound: 0, fileOpenFailed: 1
    };
    this.raiseSystem = {
        fileError: 0
    };
    this.renditionType = {
        unknown: 0, media: 1, selector: 2
    };
    this.status = {
        clear: 0, message: 1, contacting: 2, buffering: 3, init: 4, seeking: 5
    };
    this.trace = false;
    this.version = 7.0;
    this.windowType = {
        docked: 0, floating: 1, fullScreen: 2
    };
}

Object.defineProperty(Media.prototype, "addStockEvents", {
    writable: true, enumerable: true, configurable: true,
    value: function (playerObj, annot) {
        console.log("addStockEvents not defined");
    }
});

Object.defineProperty(Media.prototype, "alertFileNotFound", {
    writable: true, enumerable: true, configurable: true,
    value: function (oDoc, cFileName, bCanSkipAlert) {
        console.log("alertFileNotFound not defined");
    }
});

Object.defineProperty(Media.prototype, "alertSelectFailed", {
    writable: true, enumerable: true, configurable: true,
    value: function (oDoc, oRejects, bCanSkipAlert, bFromUser) {
        console.log("alertFileNotFound not defined");
    }
});

Object.defineProperty(Media.prototype, "argsDWIM", {
    writable: true, enumerable: true, configurable: true,
    value: function (args) {
        console.log("argsDWIM not defined");
    }
});

Object.defineProperty(Media.prototype, "canPlayOrAlert", {
    writable: true, enumerable: true, configurable: true,
    value: function (args) {
        console.log("canPlayOrAlert not defined");
    }
});

Object.defineProperty(Media.prototype, "computeFloatWinRect", {
    writable: true, enumerable: true, configurable: true,
    value: function (doc, floating, monitorType, uiSize) {
        console.log("computeFloatWinRect not defined");
    }
});

Object.defineProperty(Media.prototype, "constrainRectToScreen", {
    writable: true, enumerable: true, configurable: true,
    value: function (rect, anchorPt) {
        console.log("constrainRectToScreen not defined");
    }
});

Object.defineProperty(Media.prototype, "createPlayer", {
    writable: true, enumerable: true, configurable: true,
    value: function (args) {
        console.log("createPlayer not defined");
    }
});

Object.defineProperty(Media.prototype, "getAltTextData", {
    writable: true, enumerable: true, configurable: true,
    value: function (cAltText) {
        console.log("getAltTextData not defined");
    }
});

Object.defineProperty(Media.prototype, "getAltTextSettings", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getAltTextSettings not defined");
    }
});

Object.defineProperty(Media.prototype, "getAnnotStockEvents", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getAnnotStockEvents not defined");
    }
});

Object.defineProperty(Media.prototype, "getAnnotTraceEvents", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getAnnotTraceEvents not defined");
    }
});

Object.defineProperty(Media.prototype, "getPlayers", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getPlayers not defined");
    }
});

Object.defineProperty(Media.prototype, "getPlayerStockEvents", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getPlayerStockEvents not defined");
    }
});

Object.defineProperty(Media.prototype, "getPlayerTraceEvents", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getPlayerTraceEvents not defined");
    }
});

Object.defineProperty(Media.prototype, "getRenditionSettings", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getRenditionSettings not defined");
    }
});

Object.defineProperty(Media.prototype, "getURLData", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getURLData not defined");
    }
});

Object.defineProperty(Media.prototype, "getURLSettings", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getURLSettings not defined");
    }
});

Object.defineProperty(Media.prototype, "getWindowBorderSize", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getWindowBorderSize not defined");
    }
});

Object.defineProperty(Media.prototype, "openPlayer", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("openPlayer not defined");
    }
});

Object.defineProperty(Media.prototype, "removeStockEvents", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("removeStockEvents not defined");
    }
});

Object.defineProperty(Media.prototype, "startPlayer", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("startPlayer not defined");
    }
});

function Bookmark() {
    this.children = null; //or array
    this.color = ["RGB", 1, 0, 0];
    this.name = "";
    this.open = true;
    this.parent = null;
    this.style = 0;
}

Object.defineProperty(Bookmark.prototype, "createChild", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName, cExpr, nIndex) {
        console.log("createChild not defined");
    }
});

Object.defineProperty(Bookmark.prototype, "execute", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("execute not defined");
    }
});

Object.defineProperty(Bookmark.prototype, "insertChild", {
    writable: true, enumerable: true, configurable: true,
    value: function (oBookmark, nIndex) {
        console.log("insertChild not defined");
    }
});

Object.defineProperty(Bookmark.prototype, "remove", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("remove not defined");
    }
});

Object.defineProperty(Bookmark.prototype, "setAction", {
    writable: true, enumerable: true, configurable: true,
    value: function (cScript) {
        console.log("setAction not defined");
    }
});

function Catalog() {
    this.isIdle = false;
    this.jobs = new Array();
}

Object.defineProperty(Catalog.prototype, "getIndex", {
    writable: true, enumerable: true, configurable: true,
    value: function (cDIPath) {
        console.log("getIndex not defined");
    }
});

Object.defineProperty(Catalog.prototype, "remove", {
    writable: true, enumerable: true, configurable: true,
    value: function (oJob) {
        console.log("remove not defined");
    }
});

function CatalogJob() {
    this.path = "";
    this.type = "";//Build,Rebuild,Delete
    this.status = "";//Pending,Processing,Completed,CompletedWithErrors    
}

function Certificate() {
    this.binary = "";
    this.issuerDN = {};
    this.keyUsage = new Array();
    //kDigitalSignature kDataEncipherment kCRLSign,kNonRepudiation kKeyAgreement,
    //kEncipherOnly,kKeyEncipherment kKeyCertSign kDecipherOnly    
    this.MD5Hash = "";
    this.privateKeyValidityEnd = {};
    this.privateKeyValidityStart = {};
    this.SHA1Hash = "";
    this.serialNumber = "";
    this.subjectCN = "";
    this.subjectDN = "";
    this.ubRights = {};
    this.usage = {};
    this.validityEnd = {};
    this.validityStart = {};

}

function Rights() {
    this.mode = ""; //Evaluation,Production
    this.rights = new Array();
    //FormFillInAndSave ,FormImportExport,FormAddDelete ,SubmitStandalone,
    //SpawnTemplate, Signing, AnnotModify, AnnotImportExport, BarcodePlaintext,
    //AnnotOnline, FormOnline, EFModify       
}

function Usage() {
    this.endUserSigning = false;
    this.endUserEncryption = false;
}

var Collab = {
    addStateModel: function (cName, cUIName, oStates, cDefault, bHidden, bHistory) {
        console.log("addStateModel not implemented");
    },
    documentToStream: function (oDocument) {
        console.log("documentToStream not implemented");
    },
    removeStateModel: function (cName) {
        console.log("removeStateModel not implemented");
    }
};

function States() {
    this.cUIName = "";
    this.oIcon = {};
}

var color2 = {
    transparent: ["T"], black: ["G", 0], white: ["G", 1], red: ["RGB", 1, 0, 0],
    green: ["RGB", 0, 1, 0], blue: ["RGB", 0, 0, 1], cyan: ["CMYK", 1, 0, 0, 0],
    magenta: ["CMYK", 0, 1, 0, 0], yellow: ["CMYK", 0, 0, 1, 0], dkGray: ["G", 0.25],
    gray: ["G", 0.5], ltGray: ["G", 0.75],
    convert: function (colorArray, cColorspace) {
        console.log("convert not implemented");
    },
    equal: function (colorArray1, colorArray2) {
        console.log("equal not implemented");
    }
};

function ColorConvertAction() {
    this.action = {};//constants.actions object
    this.alias = "";
    this.colorantName = "";
    this.convertIntent = 0;
    this.convertProfile = "";
    this.embed = false;
    this.isProcessColor = false;
    this.matchAttributesAll = {};//constants.objectFlags object
    this.matchAttributesAny = 0;
    this.matchIntent = {};
    this.matchSpaceTypeAll = {};//constants.spaceFlags object
    this.matchSpaceTypeAny = 0;
    this.preserveBlack = false;
    this.useBlackPointCompensation = false;
}

function Column() {
    this.columnNum - 0;
    this.name = "";
    this.type = 0;
    this.typeName = "";
    this.value = "";
}

function ColumnInfo() {
    this.name = "";
    this.description = "";
    this.type = "";
    this.typeName = "";
}

function Connection() {

}

Object.defineProperty(Connection.prototype, "close", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("close not defined");
    }
});

Object.defineProperty(Connection.prototype, "getColumnList", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("getColumnList not defined");
    }
});

Object.defineProperty(Connection.prototype, "getTableList", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getTableList not defined");
    }
});

Object.defineProperty(Connection.prototype, "newStatement", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("newStatement not defined");
    }
});

function Data() {
    this.creationDate = {};
    this.description = "";
    this.MIMEType = "";
    this.modDate = {};
    this.name = "";
    this.path = "";
    this.size = 0;
}

function DataSourceInfo() {
    this.name = "";
    this.description = "";
}

function dbg() {
    this.bps = new Array();
}


Object.defineProperty(dbg.prototype, "c", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("c not defined");
    }
});

Object.defineProperty(dbg.prototype, "cb", {
    writable: true, enumerable: true, configurable: true,
    value: function (fileName, lineNum) {
        console.log("cb not defined");
    }
});

Object.defineProperty(dbg.prototype, "q", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("q not defined");
    }
});


Object.defineProperty(dbg.prototype, "sb", {
    writable: true, enumerable: true, configurable: true,
    value: function (fileName, lineNum, condition) {
        console.log("sb not defined");
    }
});

Object.defineProperty(dbg.prototype, "si", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("si not defined");
    }
});

Object.defineProperty(dbg.prototype, "sn", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("sn not defined");
    }
});

Object.defineProperty(dbg.prototype, "so", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("so not defined");
    }
});

Object.defineProperty(dbg.prototype, "sv", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("sv not defined");
    }
});

function Dialog() {

}

Object.defineProperty(Dialog.prototype, "enable", {
    writable: true, enumerable: true, configurable: true,
    value: function (obj) {
        console.log("enable not defined");
    }
});

Object.defineProperty(Dialog.prototype, "end", {
    writable: true, enumerable: true, configurable: true,
    value: function (str) {
        console.log("end not defined");
    }
});

Object.defineProperty(Dialog.prototype, "load", {
    writable: true, enumerable: true, configurable: true,
    value: function (obj) {
        console.log("load not defined");
    }
});

Object.defineProperty(Dialog.prototype, "store", {
    writable: true, enumerable: true, configurable: true,
    value: function (obj) {
        console.log("store not defined");
    }
});

function DirConnection() {
    this.canList = false;
    this.canDoCustomSearch = false;
    this.canDoCustomUISearch = false;
    this.canDoStandardSearch = false;
    this.groups = new Array();
    this.name = "";
    this.uiName = "";
}

function Directory() {
    this.info = {};

}

Object.defineProperty(Directory.prototype, "connect", {
    writable: true, enumerable: true, configurable: true,
    value: function (oParams, bUI) {
        console.log("connect not defined");
        return null;
    }
});

function DirectoryInformation() {
    this.dirStdEntryID = "";
    this.dirStdEntryName = "";
    this.dirStdEntryPrefDirHandlerID = "";
    this.dirStdEntryDirType = "";
    this.dirStdEntryDirVersion = "";
    this.server = "";
    this.port = 0;
    this.searchBase = "";
    this.maxNumEntries = 0;
    this.timeout = 0;
}

var zoomType = {
    none: "NoVary",
    fitP: "FitPage",
    fitW: "FitWidth",
    fitH: "fitHeight",
    fitV: "fitVisibleWidth",
    pref: "Preferred",
    refW: "ReflowWidth"
};

function Doc() {

    //custom addition
    this.pages = [];

    this.alternatePresentations = {};
    this.author = "";
    this.baseURL = "";
    this.bookmarkRoot = {};
    this.calculate = false;
    this.creationDate = new Date();
    this.creator = "";
    this.dataObjects = [];
    this.delay = false;
    this.dirty = false;
    this.disclosed = false;
    this.docID = [];
    this.documentFileName = "";
    this.dynamicXFAForm = false;
    this.external = true;
    this.fileSize = 0; //should be in bytes
    this.hidden = false;
    this.hostContainer = {};
    this.icons = null;
    this.info = {};
    this.innerAppWindowRect = [];
    this.innerDocWindowRect = [];
    this.isModal = false;
    this.keywords = {};
    this.layout = "";
    this.media = {};
    this.metadata = "";
    this.modDate = new Date();
    this.mouseX = 0;
    this.mouseY = 0;
    this.noautoComplete = false;
    this.nocache = false;
    this.numFields = 0;
    this.numPages = 0;
    this.numTemplates = 0;
    this.path = "";
    this.outerAppWindowRect = [];
    this.outerDocWindowRect = [];
    this.pageNum = 0;
    this.pageWindowRect = [];
    this.permStatusReady = false;
    this.producer = "PDFWriter";
    this.requiresFullSave = false;
    this.securityHandler = "";
    this.selectedAnnots = [];
    this.sounds = [];
    this.spellDictionaryOrder = [];
    this.spellLanguageOrder = [];
    this.subject = "";
    this.templates = [];
    this.title = "";
    this.URL = "";
    this.viewState = {};
    this.xfa = {};
    this.XFAForeground = false;
    this.zoom = 100;
    this.zoomType = zoomType.none;
}

Object.defineProperty(Doc.prototype, "addAnnot", {
    writable: true, enumerable: true, configurable: true,
    value: function (annotObj) {
        console.log("addAnnot not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "addField", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName, cFieldType, nPageNum, oCoords) {
        console.log("addField not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "addIcon", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName, icon) {
        console.log("addIcon not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "addLink", {
    writable: true, enumerable: true, configurable: true,
    value: function (oCoords) {
        console.log("addLink not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "addRecipientListCryptFilter", {
    writable: true, enumerable: true, configurable: true,
    value: function (oCryptFilter, oGroup) {
        console.log("addRecipientListCryptFilter not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "addRequirement", {
    writable: true, enumerable: true, configurable: true,
    value: function (cType, oReq) {
        console.log("addRequirement not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "addScript", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName, cScript) {
        console.log("addScript not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "addThumbnails", {
    writable: true, enumerable: true, configurable: true,
    value: function (nStart, nEnd) {
        console.log("addThumbnails not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "addWatermarkFromFile", {
    writable: true, enumerable: true, configurable: true,
    value: function (ocg) {
        console.log("addWatermarkFromFile not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "addWatermarkFromText", {
    writable: true, enumerable: true, configurable: true,
    value: function (ocg) {
        console.log("addWatermarkFromText not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "addWeblinks", {
    writable: true, enumerable: true, configurable: true,
    value: function (nStart, nEnd) {
        console.log("addWeblinks not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "bringToFront", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("bringToFront not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "calculateNow", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("calculateNow not defined");
        return null;
    }
});


Object.defineProperty(Doc.prototype, "closeDoc", {
    writable: true, enumerable: true, configurable: true,
    value: function (bNoSave) {
        console.log("closeDoc not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "colorConvertPage", {
    writable: true, enumerable: true, configurable: true,
    value: function (pageNum, actions, inkActions) {
        console.log("colorConvertPage not defined");
        return true;
    }
});

Object.defineProperty(Doc.prototype, "createDataObject", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName, cValue, cMIMEType, cCryptFilter) {
        console.log("createDataObject not defined");
    }
});

Object.defineProperty(Doc.prototype, "createTemplate", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName, nPage) {
        console.log("createTemplate not defined");
    }
});

Object.defineProperty(Doc.prototype, "deletePages", {
    writable: true, enumerable: true, configurable: true,
    value: function (nStart, nEnd) {
        console.log("deletePages not defined");
    }
});


Object.defineProperty(Doc.prototype, "embedDocAsDataObject", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName, cDoc, cCryptFilter, bUI) {
        console.log("embedDocAsDataObject not defined");
    }
});

Object.defineProperty(Doc.prototype, "embedOutputIntent", {
    writable: true, enumerable: true, configurable: true,
    value: function (outputIntentColorspace) {
        console.log("embedOutputIntent not defined");
    }
});


Object.defineProperty(Doc.prototype, "encryptForRecipients", {
    writable: true, enumerable: true, configurable: true,
    value: function (oGroups, bMetaData, bUI) {
        console.log("encryptForRecipients not defined");
        return false;
    }
});

Object.defineProperty(Doc.prototype, "encryptUsingPolicy", {
    writable: true, enumerable: true, configurable: true,
    value: function (oPolicy, oGroups, oHandler, bUI) {
        console.log("encryptUsingPolicy not defined");
        return {};
    }
});

Object.defineProperty(Doc.prototype, "exportAsFDF", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("exportAsFDF not defined");
    }
});


Object.defineProperty(Doc.prototype, "exportAsText", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("exportAsFDF not defined");
    }
});

Object.defineProperty(Doc.prototype, "exportAsXFDF", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("exportAsXFDF not defined");
    }
});

Object.defineProperty(Doc.prototype, "exportAsXFDFStr", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("exportAsXFDF not defined");
    }
});

Object.defineProperty(Doc.prototype, "exportDataObject", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("exportDataObject not defined");
    }
});

Object.defineProperty(Doc.prototype, "exportXFAData", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("exportXFAData not defined");
    }
});

Object.defineProperty(Doc.prototype, "exportXFAData", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("exportXFAData not defined");
    }
});

Object.defineProperty(Doc.prototype, "extractPages", {
    writable: true, enumerable: true, configurable: true,
    value: function (nStart, nEnd, cPath) {
        console.log("extractPages not defined");
    }
});

Object.defineProperty(Doc.prototype, "flattenPages", {
    writable: true, enumerable: true, configurable: true,
    value: function (nStart, nEnd, nNonPrint) {
        console.log("flattenPages not defined");
    }
});

Object.defineProperty(Doc.prototype, "getAnnot", {
    writable: true, enumerable: true, configurable: true,
    value: function (pageNo, cName) {
        console.log("getAnnot not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "getAnnot3D", {
    writable: true, enumerable: true, configurable: true,
    value: function (pageNo, cName) {
        console.log("getAnnot3D not defined");
        return null;
    }
});

Object.defineProperty(Doc.prototype, "getAnnots", {
    writable: true, enumerable: true, configurable: true,
    value: function (pageNo, nSortBy, nFilterBy) {
        console.log("getAnnots not defined");
        return [];
    }
});

Object.defineProperty(Doc.prototype, "getAnnots3D", {
    writable: true, enumerable: true, configurable: true,
    value: function (pageNo, nSortBy, nFilterBy) {
        console.log("getAnnots3D not defined");
        return [];
    }
});

Object.defineProperty(Doc.prototype, "getColorConvertAction", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getColorConvertAction not defined");
        return {};
    }
});

Object.defineProperty(Doc.prototype, "getDataObject", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("getDataObject not defined");
        return {};
    }
});

Object.defineProperty(Doc.prototype, "getDataObjectContents", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName, bAllowAuth) {
        console.log("getDataObjectContents not defined");
        return {};
    }
});

Object.defineProperty(Doc.prototype, "getField", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("getField not defined");
        return {};
    }
});

Object.defineProperty(Doc.prototype, "getIcon", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("getIcon not defined");
        return {};
    }
});

Object.defineProperty(Doc.prototype, "getLegalWarnings", {
    writable: true, enumerable: true, configurable: true,
    value: function (bExecute) {
        console.log("getLegalWarnings not defined");
        return {};
    }
});

Object.defineProperty(Doc.prototype, "getLinks", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage, oCoords) {
        console.log("getLinks not defined");
        return [];
    }
});

Object.defineProperty(Doc.prototype, "getNthFieldName", {
    writable: true, enumerable: true, configurable: true,
    value: function (nIndex) {
        console.log("getNthFieldName not defined");
        return "";
    }
});

Object.defineProperty(Doc.prototype, "getNthTemplate", {
    writable: true, enumerable: true, configurable: true,
    value: function (nIndex) {
        console.log("getNthTemplate not defined");
        return "";
    }
});

Object.defineProperty(Doc.prototype, "getOCGs", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage) {
        console.log("getOCGs not defined");
        return [];
    }
});

Object.defineProperty(Doc.prototype, "getOCGOrder", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getOCGOrder not defined");
        return [];
    }
});

Object.defineProperty(Doc.prototype, "getPageBox", {
    writable: true, enumerable: true, configurable: true,
    value: function (cBox, nPage) {
        console.log("getPageBox not defined");
        return [];
    }
});

Object.defineProperty(Doc.prototype, "getPageLabel", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage) {
        console.log("getPageLabel not defined");
        return {};
    }
});

Object.defineProperty(Doc.prototype, "getPageNthWord", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage, nWord, bStrip) {
        console.log("getPageNthWord not defined");
        return {};
    }
});

Object.defineProperty(Doc.prototype, "getPageNthWordQuads", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage, nWord) {
        console.log("getPageNthWordQuards not defined");
        return {};
    }
});

Object.defineProperty(Doc.prototype, "getPageNumWords", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage) {
        console.log("getPageNumWords not defined");
        return 0;
    }
});

Object.defineProperty(Doc.prototype, "getPageRotation", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage) {
        console.log("getPageRotation not defined");
        return 0;
    }
});

Object.defineProperty(Doc.prototype, "getPageTransition", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage) {
        console.log("getPageTransition not defined");
        return [];
    }
});

Object.defineProperty(Doc.prototype, "getPrintParams", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("getPrintParams not defined");
        return {};
    }
});

Object.defineProperty(Doc.prototype, "getSound", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("getSound not defined");
        return {};
    }
});

Object.defineProperty(Doc.prototype, "getTemplate", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("getTemplate not defined");
        return {};
    }
});

Object.defineProperty(Doc.prototype, "getURL", {
    writable: true, enumerable: true, configurable: true,
    value: function (cURL, bAppend) {
        console.log("getURL not defined");
    }
});

Object.defineProperty(Doc.prototype, "gotoNamedDest", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("gotoNamedDest not defined");
    }
});

Object.defineProperty(Doc.prototype, "importAnFDF", {
    writable: true, enumerable: true, configurable: true,
    value: function (cPath) {
        console.log("importAnFDF not defined");
    }
});

Object.defineProperty(Doc.prototype, "importAnFDF", {
    writable: true, enumerable: true, configurable: true,
    value: function (cPath) {
        console.log("importAnFDF not defined");
    }
});

Object.defineProperty(Doc.prototype, "importDataObject", {
    writable: true, enumerable: true, configurable: true,
    value: function (cDIPath, cCryptFilter) {
        console.log("importDataObject not defined");
    }
});

Object.defineProperty(Doc.prototype, "importIcon", {
    writable: true, enumerable: true, configurable: true,
    value: function (cDIPath, nPage) {
        console.log("importIcon not defined");
    }
});

Object.defineProperty(Doc.prototype, "importSound", {
    writable: true, enumerable: true, configurable: true,
    value: function (cDIPath) {
        console.log("importSound not defined");
    }
});

Object.defineProperty(Doc.prototype, "importTextData", {
    writable: true, enumerable: true, configurable: true,
    value: function (cPath, nRow) {
        console.log("importTextData not defined");
        return 0;
    }
});

Object.defineProperty(Doc.prototype, "importXFAData", {
    writable: true, enumerable: true, configurable: true,
    value: function (cPath) {
        console.log("importXFAData not defined");
    }
});

Object.defineProperty(Doc.prototype, "insertPages", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPath, cPath, nStart, nEnd) {
        console.log("insertPages not defined");
    }
});

Object.defineProperty(Doc.prototype, "mailDoc", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        //parse true or obj = bUI, cTo, cCc, cBcc, cSubject, cMsg
        console.log("mailDoc not defined");
    }
});

Object.defineProperty(Doc.prototype, "mailForm", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        //parse true or obj = bUI, cTo, cCc, cBcc, cSubject, cMsg
        console.log("mailForm not defined");
    }
});

Object.defineProperty(Doc.prototype, "movePage", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage, nAfter) {
        console.log("movePage not defined");
    }
});

Object.defineProperty(Doc.prototype, "newPage", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage, nWidth, nHeight) {
        console.log("newPage not defined");
    }
});

Object.defineProperty(Doc.prototype, "openDataObject", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("openDataObject not defined");
        return this;
    }
});

Object.defineProperty(Doc.prototype, "print", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("print not defined");
    }
});

Object.defineProperty(Doc.prototype, "removeDataObject", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("removeDataObject not defined");
    }
});

Object.defineProperty(Doc.prototype, "removeField", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("removeField not defined");
    }
});

Object.defineProperty(Doc.prototype, "removeIcon", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("removeIcon not defined");
    }
});


Object.defineProperty(Doc.prototype, "removeLinks", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage, oCoords) {
        console.log("removeLinks not defined");
    }
});

Object.defineProperty(Doc.prototype, "removeRequirement", {
    writable: true, enumerable: true, configurable: true,
    value: function (cType) {
        console.log("removeRequirement not defined");
    }
});

Object.defineProperty(Doc.prototype, "removeScript", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("removeScript not defined");
    }
});

Object.defineProperty(Doc.prototype, "removeTemplate", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName) {
        console.log("removeTemplate not defined");
    }
});

Object.defineProperty(Doc.prototype, "removeThumbnails", {
    writable: true, enumerable: true, configurable: true,
    value: function (nStart, nEnd) {
        console.log("removeThumbnails not defined");
    }
});

Object.defineProperty(Doc.prototype, "removeWeblinks", {
    writable: true, enumerable: true, configurable: true,
    value: function (nStart, nEnd) {
        console.log("removeWeblinks not defined");
    }
});

Object.defineProperty(Doc.prototype, "replacePages", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage, cPath, nStart, nEnd) {
        console.log("replacePages not defined");
    }
});

Object.defineProperty(Doc.prototype, "resetForm", {
    writable: true, enumerable: true, configurable: true,
    value: function (aFields) {
        console.log("resetForm not defined");
    }
});

Object.defineProperty(Doc.prototype, "saveAs", {
    writable: true, enumerable: true, configurable: true,
    value: function (cPath, cConvID, cFS, bCopy, bPrompToOverwrite) {
//cConvID Valid extensions
//com.adobe.Acrobat.eps eps
//com.adobe.Acrobat.html-3-20 html, htm
//com.adobe.Acrobat.html-4-01-css-1-00 html, htm
//com.adobe.Acrobat.jpeg jpeg ,jpg, jpe
//com.adobe.Acrobat.jp2k jpf,jpx,jp2,j2k,j2c,jpc
//com.adobe.Acrobat.doc doc
//com.callas.preflight.pdfa pdf
//com.callas.preflight.pdfx pdf
//com.adobe.Acrobat.png png
//com.adobe.Acrobat.ps ps
//com.adobe.Acrobat.rtf rft
//com.adobe.Acrobat.accesstext txt
//com.adobe.Acrobat.plain-text txt
//com.adobe.Acrobat.tiff tiff, tif
//com.adobe.Acrobat.xml-1-00 xml        
        console.log("saveAs not defined");
    }
});

Object.defineProperty(Doc.prototype, "scroll", {
    writable: true, enumerable: true, configurable: true,
    value: function (nx, ny) {
        console.log("scroll not defined");
    }
});

Object.defineProperty(Doc.prototype, "selectPageNthWord", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage, nWord, bScroll) {
        console.log("selectPageNthWord not defined");
    }
});

Object.defineProperty(Doc.prototype, "setAction", {
    writable: true, enumerable: true, configurable: true,
    value: function (cTrigger, cScript) {
        console.log("setAction not defined");
    }
});

Object.defineProperty(Doc.prototype, "setDataObjectContents", {
    writable: true, enumerable: true, configurable: true,
    value: function (cName, oStream, cCryptFilter) {
        console.log("setDataObjectContents not defined");
    }
});

Object.defineProperty(Doc.prototype, "setOCGOrder", {
    writable: true, enumerable: true, configurable: true,
    value: function (cOrderArray) {
        console.log("setOCGOrder not defined");
    }
});

Object.defineProperty(Doc.prototype, "setPageAction", {
    writable: true, enumerable: true, configurable: true,
    value: function (cTrigger, cScript) {
        console.log("setPageAction not defined");
    }
});

Object.defineProperty(Doc.prototype, "setPageBoxes", {
    writable: true, enumerable: true, configurable: true,
    value: function (cBox, nStart, nEnd, rBox) {
        console.log("setPageBoxes not defined");
    }
});

Object.defineProperty(Doc.prototype, "setPageLabels", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage, aLabel) {
        console.log("setPageLabels not defined");
    }
});

Object.defineProperty(Doc.prototype, "setPageTabOrder", {
    writable: true, enumerable: true, configurable: true,
    value: function (nPage, cOrder) {
        console.log("setPageTabOrder not defined");
    }
});

Object.defineProperty(Doc.prototype, "setPageTransitions", {
    writable: true, enumerable: true, configurable: true,
    value: function (nStart, nEnd, aTrans) {
        console.log("setPageTransitions not defined");
    }
});

Object.defineProperty(Doc.prototype, "spawnPageFromTemplate", {
    writable: true, enumerable: true, configurable: true,
    value: function (cTemplate, nPage, bRename, bOverlay, oXObject) {
        console.log("spawnPageFromTemplate not defined");
    }
});

Object.defineProperty(Doc.prototype, "submitForm", {
    writable: true, enumerable: true, configurable: true,
    value: function (url) {        
        if (app.isFDF) {
            var dd = document.getElementById("FDFXFA_Processing");
            if(dd){
                dd.style.display = "block";
            }                
            var dataArr = EcmaParser._insertFieldsToPDF("");
            var str64 = EcmaFilter.encodeBase64(dataArr);
            var form = document.getElementById("FDFXFA_Form");
            if(url){
                form.setAttribute("action",url);
            }else{
                url = window.prompt("Please Enter URL to Submit Form; \n[ Please use 'pdfdata' as named parameter for accessing filled pdf as base64 ] \n[ Please refer to JPDFFORMS documentation for defining submit URL ]");
                form.setAttribute("action",url);
            }
            form.setAttribute("action",url);                
            var textArea = document.getElementById("FDFXFA_Textarea");
            textArea.value = str64;
            form.submit();                
        }
        
    }
});

Object.defineProperty(Doc.prototype, "syncAnnotScan", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("syncAnnotScan not defined");
    }
});

function EmbeddedPDF() {
    this.messageHandler = {};
    this.postMessage = {};
}

function Error() {
    this.fileName = "";
    this.lineNumber = 0;
    this.extMessage = "";
    this.message = "";
    this.name = "";
}

Object.defineProperty(Doc.prototype, "toString", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("tostring not defined");
    }
});

//Please refer to xfa events before implementing fdf events;
//function Event(){
//    
//}

function Events() {

}

Object.defineProperty(Events.prototype, "add", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("add not defined");
    }
});

Object.defineProperty(Events.prototype, "dispatch", {
    writable: true, enumerable: true, configurable: true,
    value: function (oMediaEvent) {
        console.log("dispatch not defined");
    }
});

Object.defineProperty(Events.prototype, "remove", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("remove not defined");
    }
});

function FDF() {
    this.deleteOption = 0;
    this.isSigned = false;
    this.numEmbeddedFiles = 0;
}

Object.defineProperty(FDF.prototype, "addContact", {
    writable: true, enumerable: true, configurable: true,
    value: function (oUserEntity) {
        console.log("addContact not defined");
    }
});

Object.defineProperty(FDF.prototype, "addEmbeddedFile", {
    writable: true, enumerable: true, configurable: true,
    value: function (cDIPath, nSaveOption) {
        console.log("addEmbeddedFile not defined");
    }
});

Object.defineProperty(FDF.prototype, "addRequest", {
    writable: true, enumerable: true, configurable: true,
    value: function (cType, cReturnAddress, cName) {
        console.log("addRequest not defined");
    }
});

Object.defineProperty(FDF.prototype, "addRequest", {
    writable: true, enumerable: true, configurable: true,
    value: function (cType, cReturnAddress, cName) {
        console.log("addRequest not defined");
    }
});

Object.defineProperty(FDF.prototype, "close", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("close not defined");
    }
});

Object.defineProperty(FDF.prototype, "mail", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("mail not defined");
    }
});


Object.defineProperty(FDF.prototype, "save", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("save not defined");
    }
});

Object.defineProperty(FDF.prototype, "signatureClear", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("signatureClear not defined");
        return false;
    }
});

Object.defineProperty(FDF.prototype, "signatureSign", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        console.log("signatureSign not defined");
        return false;
    }
});

Object.defineProperty(FDF.prototype, "signatureValidate", {
    writable: true, enumerable: true, configurable: true,
    value: function (oSig, bUI) {
        console.log("signatureSign not defined");
        return {};
    }
});


function FullScreen() {

}

Object.defineProperty(FullScreen.prototype, "isFullscreen", {get: function () {
        return this.isinFullscreen;
    }, set: function (newValue) {
        if (newValue) {
            var requestFullscreen = document.body.requestFullscreen
                    || document.body.msRequestFullscreen
                    || document.body.mozRequestFullScreen
                    || document.body.webkitRequestFullscreen;
            requestFullscreen.call(document.body);
        } else {
            var exitFullscreen = document.exitFullscreen
                    || document.msExitFullscreen
                    || document.mozCancelFullScreen
                    || document.webkitCancelFullScreen;
            exitFullscreen.call(document);
        }
    }, configurable: true, enumerable: true});

Object.defineProperty(FullScreen.prototype, "isFullscreenEnabled", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        return document.fullscreenEnabled || document.msFullscreenEnabled
                || document.mozFullScreenEnabled || document.webkitFullscreenEnabled;
    }
});

Object.defineProperty(FullScreen.prototype, "isinFullscreen", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        return !!(document.fullscreenElement || document.msFullscreenElement
                || document.mozFullScreenElement || document.webkitFullscreenElement);
    }
});

Object.defineProperty(FullScreen.prototype, "toggleFullscreen", {
    writable: true, enumerable: true, configurable: true,
    value: function () {
        if (!this.isinFullscreen()) {
            var requestFullscreen = document.body.requestFullscreen
                    || document.body.msRequestFullscreen
                    || document.body.mozRequestFullScreen
                    || document.body.webkitRequestFullscreen;
            requestFullscreen.call(document.body);
        } else {
            var exitFullscreen = document.exitFullscreen
                    || document.msExitFullscreen
                    || document.mozCancelFullScreen
                    || document.webkitCancelFullScreen;
            exitFullscreen.call(document);
        }
    }
});


function createXFAPDF() {
    var pdfDoc = new PdfDocument();
    var page = new PdfPage();
    pdfDoc.addPage(page);
    var text = new PdfText(70, 70, "Helvetica", 20, "Please wait...");
    page.addText(text);
    text = new PdfText(70, 110, "Helvetica", 11, "If this message is not eventually replaced by proper contents of the document, your PDF");
    page.addText(text);
    text = new PdfText(70, 124, "Helvetica", 11, "viewer may not be able to display this type of document.");
    page.addText(text);
    text = new PdfText(70, 150, "Helvetica", 11, "You can upgrade to the latest version of Adobe Reader for Windows, Mac, or Linux by");
    page.addText(text);
    text = new PdfText(70, 164, "Helvetica", 11, "visiting http://www.adobe.com/go/reader_download.");
    page.addText(text);
    text = new PdfText(70, 190, "Helvetica", 11, "For more assistance with Adobe Reader visit http://www.adobe.com/go/acrreader.");
    page.addText(text);
    text = new PdfText(70, 220, "Helvetica", 7.5, "Windows is either a registered trademark or a trademark of Microsoft Corporation in the United States and/or other countries. Mac is a trademark ");
    page.addText(text);
    text = new PdfText(70, 229, "Helvetica", 7.5, "of Apple Inc., registered in the United States and other countries. Linux is the registered trademark of Linus Torvalds in the U.S. and other ");
    page.addText(text);
    text = new PdfText(70, 238, "Helvetica", 7.5, "countries.");
    page.addText(text);

//    console.log(EcmaPDF.decode64(templateStr));
    var xdpStr = generateXDP();
    var str = pdfDoc.createPdfString(xdpStr);
    var hrefStr = "data:application/octet-stream;charset=utf-8;base64," + EcmaPDF.encode64(str);
    var title = document.title;
    title = title.length > 0 ? title : "File_Edited";
    var userAgent = "" + navigator.userAgent;
    if (userAgent.indexOf("Edge") !== -1 || userAgent.indexOf("MSIE ") !== -1) {
        var blobObject = new Blob([str]);
        window.navigator.msSaveBlob(blobObject, title + ".pdf");
        return;
    }
    var link = DCE("a");
    link.setAttribute("download", title + ".pdf");
    link.setAttribute("href", hrefStr);
    document.getElementById("preRenderer").appendChild(link);
    if ("click" in link) {
        link.click();
    } else { //hack for safari
        var clk = document.createEvent("MouseEvent");
        clk.initEvent("click", true, true);
        link.dispatchEvent(clk);
    }
    link.setAttribute("href", "");
}


function generateXDP() {

    var createTool = "AdobeLiveCycleDesignerES_V10.0";
    var pdfTool = "JPDF2HTML5";
    var createUUID = FormCalc.uuid();
    var d = new Date();
    var createDATE = d.toISOString();

    var prefix = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    prefix += "<?xfa generator=\"" + createTool + "\" APIVersion=\"3.5.12002.0\"?>";
    prefix += "<xdp:xdp xmlns:xdp=\"http://ns.adobe.com/xdp/\" timeStamp=\"" + createDATE + "\" uuid=\"" + createUUID + "\">";

    var xmpMeta = "<x:xmpmeta xmlns:x=\"adobe:ns:meta/\" x:xmptk=\"Adobe XMP Core 5.2-c001 63.139439, 2011/06/07-10:39:26\">";
    xmpMeta += "<rdf:RDF xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\">";
    xmpMeta += "<rdf:Description xmlns:xmp=\"http://ns.adobe.com/xap/1.0/\" rdf:about=\"\">";
    xmpMeta += "<xmp:MetadataDate>" + createDATE + "</xmp:MetadataDate>";
    xmpMeta += "<xmp:CreatorTool>" + createTool + "</xmp:CreatorTool>";
    xmpMeta += "</rdf:Description>";
    xmpMeta += "<rdf:Description xmlns:pdf=\"http://ns.adobe.com/pdf/1.3/\" rdf:about=\"\">";
    xmpMeta += "<pdf:Producer>" + pdfTool + "</pdf:Producer>";
    xmpMeta += "</rdf:Description>";
    xmpMeta += "<rdf:Description xmlns:xmpMM=\"http://ns.adobe.com/xap/1.0/mm/\" rdf:about=\"\">";
    xmpMeta += "<xmpMM:DocumentID>" + createUUID + "</xmpMM:DocumentID>";
    xmpMeta += "</rdf:Description>";
    xmpMeta += "<rdf:Description xmlns:desc=\"http://ns.adobe.com/xfa/promoted-desc/\" rdf:about=\"\">";
    xmpMeta += "<desc:version rdf:parseType=\"Resource\">";
    xmpMeta += "<rdf:value>10.0.2.20120224.1.869952.867557</rdf:value>";
    xmpMeta += "<desc:ref>/template/subform[1]</desc:ref>";
    xmpMeta += "</desc:version>";
    xmpMeta += "</rdf:Description>";
    xmpMeta += "</rdf:RDF>";
    xmpMeta += "</x:xmpmeta>";

    if (templateStr.indexOf("<?xml") === 0) {
        templateStr = templateStr.substring(templateStr.indexOf(">") + 1);
    }

    var configDump = document.getElementById("configDump");
    var configStr = "";
    if (configDump) {
        configStr = configDump.textContent;
        configStr = EcmaPDF.decode64(configStr);
    }

    var localeSetStr = document.getElementById("localeSetDump").textContent;
    localeSetStr = EcmaPDF.decode64(localeSetStr);

    var dataStr = "";

    var dataPrefix = "<xfa:datasets xmlns=\"\" xmlns:xfa=\"http://www.xfa.org/schema/xfa-data/1.0/\"><xfa:data>";
    var dataSuffix = "</xfa:data></xfa:datasets>";
    var gData = {};
    var dd = generateXDPDataFromSom(xfa.form, gData);
    var gd = "";
    for (var gName in gData) {
        gd += "<" + gName + ">" + gData[gName] + "</" + gName + ">";
    }
    if (dd.length > 0) {
        if (gd.length > 0) {
            var end = dd.indexOf(">") + 1;
            var ee = dd.substring(0, end);
            dd = ee + gd + dd.substring(end);
        }
        dataStr = dataPrefix + dd + dataSuffix;
    }

    return prefix + "\n" + templateStr + "\n" + configStr + "\n" + dataStr + "\n" + localeSetStr + "\n" + xmpMeta + "\n" + "</xdp:xdp>\n\n";

}

