export default class OnclickEvent {
    
    displayForm() {
        const hideForm = document.querySelectorAll("#hide-form")

        for(let i = 0; i < hideForm.length; i ++) {
            if(hideForm[i]) {
                hideForm[i].addEventListener("click", () => {
                    let answerForm = hideForm[i].nextElementSibling;

                    if(answerForm) {
                        if(answerForm.previousElementSibling.value == "OFF") {
                            answerForm.previousElementSibling.value = "ON";
                            answerForm.style.display = "none";
                        }
                
                        else {
                            answerForm.previousElementSibling.value = "OFF";

                            if(screen.width > 400) {
                                answerForm.style.display = "initial";
                            }

                            else {
                                answerForm.style.display = "inline-block";
                                answerForm.style.height = "30rem";
                                answerForm.style.overflow = "scroll";
                            }
                        }
                    }
                });
            }
        }
    }

    displayAnswers() {
        const hideAnswers = document.querySelectorAll("#hide-answers")

        for(let i = 0; i < hideAnswers.length; i ++) {
            if(hideAnswers[i]) {
                hideAnswers[i].addEventListener("click", () => {
                    let answerIcon = hideAnswers[i].querySelector("i")
                    let answerContent = hideAnswers[i].nextElementSibling;

                    if(answerContent) {
                        if(answerContent.previousElementSibling.value == "OFF") {
                            answerContent.previousElementSibling.value = "ON";
                            answerContent.style.display = "none";
                            answerIcon.classList.remove("fa-caret-down");
                            answerIcon.classList.add("fa-caret-right");
                        }

                        else {
                            answerContent.previousElementSibling.value = "OFF";
                            answerContent.style.display = "flex";
                            answerContent.style.flexDirection = "row";
                        }
                    }
                });
            }
        }
    }
    
//*****END OF THE CLASS*****//
}