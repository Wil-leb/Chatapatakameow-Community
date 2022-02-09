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

//*****G. Table label modification for mobile (portrait format)*****//
function changeDataLabel() {
    const userEmailrows = document.querySelectorAll("#userEmail")
    const commentIdrows = document.querySelectorAll("#commentId")
    
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
}