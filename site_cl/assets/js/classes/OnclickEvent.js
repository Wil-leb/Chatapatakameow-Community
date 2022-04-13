export default class OnclickEvent {

//*****A. Comment answer display*****//
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

                            if(answerIcon) {
                                answerIcon.classList.remove("fa-caret-down")
                                answerIcon.classList.add("fa-caret-right")
                            }
                        }

                        else {
                            hideAnswer.value = "OFF"
                            answerContent.style.display = "initial"

                            if(answerIcon) {
                                answerIcon.classList.remove("fa-caret-right")
                                answerIcon.classList.add("fa-caret-down")
                            }
                        }
                    }
                });
            }
        }
    }

//*****B. Dialog closure*****//
    closeDialog() {
        const dialog = document.getElementsByTagName("dialog")
                        
        for(let i = 0; i < dialog.length; i ++) {
            if(dialog[i]) {
                const closeButton = dialog[i].querySelector("#close")

                closeButton.addEventListener("click", () => {
                    dialog[i].previousElementSibling.value = "ON"
                    dialog[i].style.visibility = "hidden"
                    
                    if(document.querySelector(".comment-content")) {
                        document.querySelector(".comment-content").style.visibility = "visible"
                    }

                    if(document.querySelector(".comment-form")) {
                        document.querySelector(".comment-form").style.visibility = "visible"
                    }
                });
            }
        }
    }

//*****C. Dialog opening*****//
    openDialog() {
        const hideContent = document.querySelectorAll("#hide-content")

        for(let i = 0; i < hideContent.length; i ++) {
            if(hideContent[i]) {
                hideContent[i].addEventListener("click", () => {
                    if(document.querySelector(".comment-content")) {
                        document.querySelector(".comment-content").style.visibility = "hidden"
                    }

                    if(document.querySelector(".comment-form")) {
                        document.querySelector(".comment-form").style.visibility = "hidden"
                    }

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

//*****D. Link copy *****/
    copyLink() {
        const copyLink = document.getElementById("copy-link")
        
        if(copyLink) {
            copyLink.addEventListener("click", () => {
                const tempElement = document.createElement("p")
                tempElement.textContent = navigator.clipboard.writeText(window.location.href)
                document.body.appendChild(tempElement)

                const range = document.createRange()
                range.setStartBefore(tempElement)
                range.setEndAfter(tempElement)

                const selection = window.getSelection()
                selection.removeAllRanges()
                selection.addRange(range)

                document.execCommand("copy")
                document.body.removeChild(tempElement)
            });
        }
    }
    
//*****END OF THE CLASS*****//
}