export default class OninputEvent {

    constructor() {
        this.loginRegex = /^[\wáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\-_]+$/
        this.albumRegex = /^[\wáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\-\/();,:.!?\'&"\s]+$/
        this.commentRegex = /^[\wáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\~\-_\/()\[\]{}@#+*=\^\%;,:.!?\'&"\s]+$/
    }

//*****A. Email creation and modification*****//
    emailMessages() {
        const email = document.getElementsByClassName("email")
        const confirmEmail = document.getElementsByClassName("confirm-email")

        for(let i = 0; i < email.length; i ++) {
            if(email[i]) {
                email[i].addEventListener("input", () => {
                    let mailDiv = email[i].nextElementSibling

                    if(mailDiv) {
                        if(email[i].value) {
                            if(!/^[\w!?#$%&'*+/=^_`{}|~-]([\.\w!?#$%&'*+/=^_`{}|~-]?)+@[\w\d-]+\.[\w\d-]+$/.test(email[i].value)) {
                                mailDiv.textContent = "Le format de l'adresse électronique est invalide."
                                mailDiv.style.color = "red"
                            }
                    
                            else {
                                mailDiv.textContent = "Le format de l'adresse électronique est valide."
                                mailDiv.style.color = "green"
                            }
                        }

                        else {
                            mailDiv.textContent = ""
                        }
                    }
                });
            }
        }

        for(let i = 0; i < confirmEmail.length; i ++) {
            if(confirmEmail[i]) {
                confirmEmail[i].addEventListener("input", () => {
                    let mailConfirm = confirmEmail[i].nextElementSibling

                    if(mailConfirm) {
                        if(email[i].value && confirmEmail[i].value) {
                            if(email[i].value != confirmEmail[i].value) {
                                mailConfirm.textContent = "L'adresse électronique et sa confirmation doivent correspondre."
                                mailConfirm.style.color = "red"
                            }
            
                            else {
                                mailConfirm.textContent = "L'adresse électronique et sa confirmation correspondent bien."
                                mailConfirm.style.color = "green"
                            }
                        }

                        else {
                            mailConfirm.textContent = ""
                        }
                    }
                });
            }
        }
    }
    
//*****B. Login creation and modification*****//
    loginMessages() {
        const login = document.getElementsByClassName("login")
        const confirmLogin = document.getElementsByClassName("confirm-login")
        const commentLogin = document.getElementsByClassName("comment-login")

        for(let i = 0; i < login.length; i ++) {
            if(login[i]) {
                login[i].addEventListener("input", () => {
                    let loginDiv = login[i].nextElementSibling

                    if(loginDiv) {
                        if(login[i].value) {
                            if(!this.loginRegex.test(login[i].value)) {
                                loginDiv.textContent = "Caractères autorisés pour le pseudo : lettres, chiffres, tirets et underscores."
                                loginDiv.style.color = "red"
                            }
                    
                            else if(login[i].value.length < 3 || login[i].value.length > 10) {
                                loginDiv.textContent = "Le pseudo doit contenir entre trois et dix caractères."
                                loginDiv.style.color = "red"
                            }
                    
                            else {
                                loginDiv.textContent = "Le format du pseudo est valide."
                                loginDiv.style.color = "green"
                            }
                        }

                        else {
                            loginDiv.textContent = ""
                        }
                    }
                });
            }
        }

        for(let i = 0; i < confirmLogin.length; i ++) {
            if(confirmLogin[i]) {
                confirmLogin[i].addEventListener("input", () => {
                    let loginConfirm = confirmLogin[i].nextElementSibling

                    if(loginConfirm) {
                        if(login[i].value && confirmLogin[i].value) {
                            if(login[i].value != confirmLogin[i].value) {
                                loginConfirm.textContent = "Le pseudo et sa confirmation doivent correspondre."
                                loginConfirm.style.color = "red"
                            }
            
                            else {
                                loginConfirm.textContent = "Le pseudo et sa confirmation correspondent bien."
                                loginConfirm.style.color = "green"
                            }
                        }

                        else {
                            loginConfirm.textContent = ""
                        }
                    }
                });
            }
        }

        for(let i = 0; i < commentLogin.length; i ++) {
            if(commentLogin[i]) {
                commentLogin[i].addEventListener("input", () => {
                    let commLogindiv = commentLogin[i].nextElementSibling

                    if(commLogindiv) {
                        if(commentLogin[i].value) {
                            if(!this.loginRegex.test(commentLogin[i].value)) {
                                commLogindiv.textContent = "Caractères autorisés pour le pseudo : lettres, chiffres, tirets et underscores."
                                commLogindiv.style.color = "red"
                            }
                    
                            else {
                                commLogindiv.textContent = "Le format du pseudo est valide."
                                commLogindiv.style.color = "green"
                            }
                        }

                        else {
                            commLogindiv.textContent = ""
                        }
                    }
                });
            }
        }
    }
    
//*****C. Password creation and modification*****//
    passwordMessages() {
        const password = document.getElementsByClassName("password")
        const confirmPassword = document.getElementsByClassName("confirm-password")

        for(let i = 0; i < confirmPassword.length; i ++) {
            if(confirmPassword[i]) {
                confirmPassword[i].addEventListener("input", () => {
                    let passwordConfirm = confirmPassword[i].nextElementSibling

                    if(passwordConfirm) {
                        if(password[i].value && confirmPassword[i].value) {
                            if(password[i].value != confirmPassword[i].value) {
                                passwordConfirm.textContent = "Le mot de passe et sa confirmation doivent correspondre."
                                passwordConfirm.style.color = "red"
                            }
            
                            else {
                                passwordConfirm.textContent = "Le mot de passe et sa confirmation correspondent bien."
                                passwordConfirm.style.color = "green"
                            }
                        }

                        else {
                            passwordConfirm.textContent = ""
                        }
                    }
                });
            }
        }
    }
    
//*****D. Title and description creation and modification*****//
    albumMessages() {
        const title = document.getElementsByClassName("album-title")
        const description = document.getElementsByClassName("album-description")
    
        for(let i = 0; i < title.length; i ++) {
            if(title[i]) {
                title[i].addEventListener("input", () => {
                    let titleLength = title[i].nextElementSibling.nextElementSibling

                    if(titleLength) {
                        if(title[i].value) {
                            if(!this.albumRegex.test(title[i].value)) {
                                titleLength.textContent = "Caractères autorisés pour le titre : lettres, chiffres, tirets, slash, parenthèses, point-virgules, virgules, doubles points, points, points d'exclamation, points d'interrogation, apostrophes, esperluettes, guillemets droits et espaces."
            
                                titleLength.style.color = "red"
                            }
            
                            else {
                                titleLength.textContent = "Le format du titre est valide."
                                titleLength.style.color = "green"
                            }
                        }

                        else {
                            titleLength.textContent = ""
                        }
                    }
                });
            }
        }

        for(let i = 0; i < description.length; i ++) {
            if(description[i]) {
                description[i].addEventListener("input", () => {
                    let descrLength = description[i].nextElementSibling.nextElementSibling

                    if(descrLength) {
                        if(description[i].value) {
                            if(!this.albumRegex.test(description[i].value)) {
                                descrLength.textContent = "Caractères autorisés pour la description : lettres, chiffres, tirets, slash, parenthèses, point-virgules, virgules, doubles points, points, points d'exclamation, points d'interrogation, apostrophes, esperluettes, guillemets droits et espaces."

                                descrLength.style.color = "red"
                            }

                            else {
                                descrLength.textContent = "Le format de la description est valide."
                                descrLength.style.color = "green"
                            }
                        }
                    
                        else {
                            descrLength.textContent = ""
                        }
                    }
                });
            }
        }
    }

//*****E. Comments and answers creation and modification*****//
    commentMessages() {
        const comment = document.getElementsByClassName("comment")
        const answer = document.getElementsByClassName("answer")

        for(let i = 0; i < comment.length; i ++) {
            if(comment[i]) {
                comment[i].addEventListener("input", () => {
                    let commentDiv = comment[i].nextElementSibling

                    if(commentDiv) {
                        if(comment[i].value) {
                            if(!this.commentRegex.test(comment[i].value)) {
                                commentDiv.textContent = 'Caractères autorisés pour le commentaire : lettres, chiffres, tilde, tirets, underscores, slash, parenthèses, crochets, accolades, arobases, dièses, signes "+", signes "=", astérisques, accents circonflexes, signes "%", point-virgules, virgules, doubles points, points, points d\'exclamation, points d\'interrogation, apostrophes, esperluettes, guillemets droits et espaces.'

                                commentDiv.style.color = "red"
                            }

                            else {
                                commentDiv.textContent = "Le format du commentaire est valide."
                                commentDiv.style.color = "green"
                            }
                        }

                        else {
                            commentDiv.textContent = ""
                        }
                    }
                });
            }
        }

        for(let i = 0; i < answer.length; i ++) {
            if(answer[i]) {
                answer[i].addEventListener("input", () => {
                    let answerDiv = answer[i].nextElementSibling

                    if(answerDiv) {
                        if(answer[i].value) {
                            if(!this.commentRegex.test(answer[i].value)) {
                                answerDiv.textContent = 'Caractères autorisés pour la réponse : lettres, chiffres, tilde, tirets, underscores, slash, parenthèses, crochets, accolades, arobases, dièses, signes "+", signes "=", astérisques, accents circonflexes, signes "%", point-virgules, virgules, doubles points, points, points d\'exclamation, points d\'interrogation, apostrophes, esperluettes, guillemets droits et espaces.'

                                answerDiv.style.color = "red"
                            }

                            else {
                                answerDiv.textContent = "Le format de la réponse est valide."
                                answerDiv.style.color = "green"
                            }
                        }

                        else {
                            answerDiv.textContent = ""
                        }
                    }
                });
            }
        }
    }

//*****END OF THE CLASS*****//
}