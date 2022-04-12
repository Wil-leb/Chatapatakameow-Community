//*****A. Registration confirm*****//

function confirmRegistration(event) {
    check = confirm("Confirmes-tu ton inscription ?")

    if(check) {
        event.submit()
    }

    else {
        event.preventDefault()
    }
}

//*****B. Album publication confirm*****//

function confirmAlbaddition(event) {
    check = confirm("Confirmes-tu la publication de cet album ?")

    if(check) {
        event.submit()
    }

    else {
        event.preventDefault()
    }
}

//*****C. Modification confirm*****//

function confirmChange(event) {
    check = confirm("Confirmes-tu cette modification ?")

    if(check) {
        event.submit()
    }

    else {
        event.preventDefault()
    }
}

//*****D. Picture addition confirm*****//

function confirmPicaddition(event) {
    check = confirm("Confirmes-tu cet ajout ?")

    if(check) {
        event.submit()
    }

    else {
        event.preventDefault()
    }
}

//*****E. Comment and answer addition confirm*****//

function confirmCommaddition(event) {
    check = confirm("Confirmes-tu la publication de ce commentaire ?")

    if(check) {
        event.submit()
    }

    else {
        event.preventDefault()
    }
}

function confirmAnsweraddition(event) {
    check = confirm("Confirmes-tu la publication de cette réponse ?")

    if(check) {
        event.submit()
    }

    else {
        event.preventDefault()
    }
}

//*****F. Deletion confirm*****//

function confirmDeletion(event) {
    check = confirm("Confirmes-tu cette suppression ?")

    if(check) {
        event.submit()
    }

    else {
        event.preventDefault()
    }
}

//*****G. Suspension confirm*****//

function confirmSuspension(event) {
    check = confirm("Confirmes-tu cette suspension ?")

    if(check) {
        event.submit()
    }

    else {
        event.preventDefault()
    }
}

//*****G. Reactivation confirm*****//

function confirmReactivation(event) {
    check = confirm("Confirmes-tu cette réactivation ?")

    if(check) {
        event.submit()
    }

    else {
        event.preventDefault()
    }
}

//*****I. Table label and header modifications*****//
function changeDataLabel() {
    const userEmailrows = document.querySelectorAll("#userEmail")
    const commentDaterows = document.querySelectorAll("#commentDate")
    const answerIdrows = document.querySelectorAll("#answerId")
    const commentIdrows = document.querySelectorAll("#commentId")
    const copyButton = document.getElementById("copy-link")
    const shareButton = document.querySelector(".share")
    const reportButtons = document.getElementsByClassName("report")
    const deleteButtons = document.getElementsByClassName("delete")
    const editButtons = document.getElementsByClassName("edit")
    const replyButtons = document.getElementsByClassName("reply")
    const answerButtons = document.getElementsByClassName("answers")
    const reportColumn = document.getElementById("reportColumn")
    
    if(screen.width < 500) {
        if(userEmailrows) {
            for(let userEmailrow of userEmailrows) {
                userEmailrow.dataset.label = "Adr. élec."
            }
        }
    }

    if(screen.width < 450) {
        if(commentDaterows) {
            for(let commentDaterow of commentDaterows) {
                commentDaterow.dataset.label = "Date comm."
            }
        }

        if(answerIdrows) {
            for(let answerIdrow of answerIdrows) {
                answerIdrow.dataset.label = "Réf. réponse"
            }
        }

        if(commentIdrows) {
            for(let commentIdrow of commentIdrows) {
                commentIdrow.dataset.label = "Réf. commentaire"
            }
        }
    }

    if(screen.width < 560) {
        // if(copyButton) {
        //     copyButton.innerHTML = "<i class='fa-solid fa-link-simple'></i>"
        // }

        if(shareButton) {
            shareButton.innerHTML = "<i class='fa-solid fa-share'></i>"
        }

        if(reportButtons) {
            for(let reportButton of reportButtons) {
                reportButton.innerHTML = "<i class='fa-solid fa-circle-minus'></i>"
            }
        }

        if(deleteButtons) {
            for(let deleteButton of deleteButtons) {
                deleteButton.innerHTML = "<i class='fas fa-trash-alt'></i>"
            }
        }

        if(editButtons) {
            for(let editButton of editButtons) {
                editButton.innerHTML = "<i class='fas fa-pen'></i>"
            }
        }

        if(replyButtons) {
            for(let replyButton of replyButtons) {
                replyButton.innerHTML = "<i class='fas fa-reply'></i>"
            }
        }

        if(answerButtons) {
            for(let answerButton of answerButtons) {
                answerButton.innerHTML = "<i class='fas fa-caret-right'></i>"
            }
        }
    }

    if(screen.width > 767 && screen.width < 801) {
        if(reportColumn) {
            reportColumn.textContent = "Signal."
        }
    }
}

//*****J. Color change for reports number*****//
function changeReportColour() {
    const reportCounts = document.querySelectorAll("#reports-number")

    for(let reportCount of reportCounts) {
        if(reportCount) {
            if(reportCount.textContent <= 3) {
                reportCount.style.color = "green"
            }

            else if(reportCount.textContent > 3 && reportCount.textContent <= 9) {
                reportCount.style.color = "orange"
            }

            else if(reportCount.textContent >= 10) {
                reportCount.style.color = "red"
            }
        }
    }
}