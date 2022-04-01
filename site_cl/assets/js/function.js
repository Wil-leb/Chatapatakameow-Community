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
    const commentIdrows = document.querySelectorAll("#commentId")
    const reportColumn = document.getElementById("reportColumn")
    
    for(let userEmailrow of userEmailrows) {
        if(screen.width < 500) {
            userEmailrow.dataset.label = "Adr. élec."
        }
    }

    for(let commentIdrow of commentIdrows) {
        if(screen.width < 450) {
            commentIdrow.dataset.label = "Réf. commentaire"
        }
    }

    if(reportColumn) {
        if(screen.width > 767 && screen.width < 801) {
            reportColumn.textContent = "Signal."
        }
    }
}

//*****J. Color change for reports number*****//
function changeDataLabel() {
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