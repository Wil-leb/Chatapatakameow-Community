export default class OnclickEvent {
    
    displayForm() {
        const hideForm = document.querySelectorAll("#hide-form")

        for(let i = 0; i < hideForm.length; i ++) {
            if(hideForm[i]) {
                hideForm[i].addEventListener("click", () => {
                    const answerForm = hideForm[i].nextElementSibling

                    if(answerForm) {
                        if(answerForm.previousElementSibling.value == "OFF") {
                            answerForm.previousElementSibling.value = "ON"
                            answerForm.style.display = "none"
                        }
                
                        else {
                            answerForm.previousElementSibling.value = "OFF"
                            answerForm.style.display = "initial"

                            // if(screen.width > 400) {
                            //     answerForm.style.display = "initial"
                            // }

                            // else {
                            //     answerForm.style.display = "inline-block"
                            //     answerForm.style.height = "30rem"
                            //     answerForm.style.overflow = "scroll"
                            // }
                        }
                    }
                });
            }
        }
    }

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
                    dialog[i].previousElementSibling.removeAttribute("disabled")
                    dialog[i].style.visibility = "hidden"
                });
            }
        }
    }

    openDialog() {
        const hideContent = document.querySelectorAll("#hide-content")

        for(let i = 0; i < hideContent.length; i ++) {
            if(hideContent[i]) {
                hideContent[i].addEventListener("click", () => {
                    const content = hideContent[i].nextElementSibling

                    if(content) {
                        if(content.previousElementSibling.value == "OFF") {
                            content.previousElementSibling.value = "ON"
                            content.style.visibility = "hidden"
                        }

                        else {
                            content.previousElementSibling.value = "OFF"
                            content.previousElementSibling.setAttribute("disabled", "")
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

    // closeDialog() {
    //     const dialog = document.getElementsByTagName("dialog")
    //     // const closeButton = document.querySelectorAll("#close")
                        
    //     for(let i = 0; i < dialog.length; i ++) {
    //         if(dialog[i]) {
    //             const closeButton = dialog[i].querySelector("#close")

    //             closeButton.addEventListener("click", () => {
    //                 dialog.previousElementSibling.value = "ON"
    //                 dialog.previousElementSibling.removeAttribute("disabled")
    //                 dialog.style.visibility = "hidden"
    //             });
    //         }
    //     }
    // }
    
//*****END OF THE CLASS*****//
}