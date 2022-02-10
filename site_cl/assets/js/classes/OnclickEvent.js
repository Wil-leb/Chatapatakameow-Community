export default class OnclickEvent {

    displayAnswers() {
        const parentDiv = document.querySelectorAll(".comment-content")
        
        for(let i = 0; i < parentDiv.length; i ++) {
            if(parentDiv[i]) {
                const buttonDiv = parentDiv[i].querySelector(".action-buttons:nth-of-type(2)")
                const hideAnswer = buttonDiv.querySelector("#hide-answers")
                const answerIcon = hideAnswer.querySelector("i")

                hideAnswer.addEventListener("click", () => {
                    const answerContent = buttonDiv.nextElementSibling
                    
                    if(answerContent) {
                        if(hideAnswer.value == "OFF") {
                            hideAnswer.value = "ON"
                            answerContent.style.display = "none"
                            answerIcon.classList.remove("fa-caret-down")
                            answerIcon.classList.add("fa-caret-right")
                        }

                        else {
                            hideAnswer.value = "OFF"
                            answerContent.style.display = "initial"
                            answerIcon.classList.remove("fa-caret-right")
                            answerIcon.classList.add("fa-caret-down")
                        }
                    }
                });
            }
        }
    }

    closeDialog() {
        const dialog = document.getElementsByTagName("dialog")
                        
        for(let i = 0; i < dialog.length; i ++) {
            if(dialog[i]) {
                const closeButton = dialog[i].querySelector("#close")

                closeButton.addEventListener("click", () => {
                    dialog[i].previousElementSibling.value = "ON"
                    dialog[i].style.visibility = "hidden"
                    document.querySelector("body").style.visibility = "visible"
                });
            }
        }
    }

    openDialog() {
        const hideContent = document.querySelectorAll("#hide-content")

        for(let i = 0; i < hideContent.length; i ++) {
            if(hideContent[i]) {
                hideContent[i].addEventListener("click", () => {
                    document.querySelector("body").style.visibility = "hidden"

                    const content = hideContent[i].nextElementSibling

                    if(content) {
                        if(content.previousElementSibling.value == "OFF") {
                            content.previousElementSibling.value = "ON"
                            content.style.visibility = "hidden"
                        }

                        else {
                            content.previousElementSibling.value = "OFF"
                            content.style.visibility = "visible"
                        }

                        const closeButton = hideContent[i].nextElementSibling.querySelector("#close")
                        
                        if(closeButton) {
                            this.closeDialog()
                        }
                    }
                });
            }
        }
    }
    
//*****END OF THE CLASS*****//
}