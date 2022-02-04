import Ajax from "./classes/Ajax.js";
import FontAwesome from "./classes/FontAwesome.js";
import OninputEvent from "./classes/OninputEvent.js";
import OnkeyupEvent from "./classes/OnkeyupEvent.js";
import OnchangeEvent from "./classes/OnchangeEvent.js";
import OnclickEvent from "./classes/OnclickEvent.js";

const AjaxClass = new Ajax();
const FontAwesomeClass = new FontAwesome();
const OninputEventClass = new OninputEvent();
const OnkeyupEventClass = new OnkeyupEvent();
const OnchangeEventClass = new OnchangeEvent();
const OnclickEventClass = new OnclickEvent();

document.addEventListener("DOMContentLoaded", () => {
    FontAwesomeClass.createArrows();

    OninputEventClass.emailMessages();
    OninputEventClass.loginMessages();
    OninputEventClass.passwordMessages();
    OninputEventClass.albumMessages();
    OninputEventClass.commentMessages();
    
    OnkeyupEventClass.lengthMessages();
    
    OnchangeEventClass.pictureMessages();

    OnclickEventClass.displayForm();
    OnclickEventClass.displayAnswers();

    let likeButton = document.getElementsByClassName("like");
    for(let i= 0; i < likeButton.length; i ++) {
        likeButton.item(i).addEventListener("click", AjaxClass.like());
        return;
    }

    let dislikeButton = document.getElementsByClassName("dislike");
    for(let i = 0; i < dislikeButton.length; i ++) {
        dislikeButton.item(i).addEventListener("click", AjaxClass.dislike());
        return;
    }
});

changeDataLabel();