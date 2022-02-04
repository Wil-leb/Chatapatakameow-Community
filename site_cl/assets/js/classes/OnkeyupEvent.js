export default class OnkeyupEvent {
    
//*****Title and description length*****//
    lengthMessages() {
        const title = document.getElementsByClassName("album-title")
        const description = document.getElementsByClassName("album-description")

        for(let i = 0; i < title.length; i ++) {
            if(title[i]) {
                title[i].addEventListener("keyup", () => {
                    let titleCount = 30 - title[i].value.length;
                    let titleDiv = title[i].nextElementSibling;

                    if(titleDiv) {
                        if(title[i].value) {
                            titleDiv.textContent = `${titleCount} caractères restants`;

                            if(titleCount > 0 && titleCount < 30 || titleCount == 0) {
                                titleDiv.style.color = "green";

                                if(titleCount <= 1) {
                                    titleDiv.textContent = `${titleCount} caractère restant`;
                                }
                            }

                            else if(titleCount < 0) {
                                titleDiv.textContent = "Le titre ne doit pas dépasser 30 caractères, espaces comprises.";
                                titleDiv.style.color = "red";
                            }
                        }

                        else {
                            titleDiv.style.color = "inherit";
                            titleDiv.textContent = "30 caractères restants";
                        }
                    }
                });
            }
        }

        for(let i = 0; i < description.length; i ++) {
            if(description[i]) {
                description[i].addEventListener("keyup", () => {
                    let descrCount = 200 - description[i].value.length;
                    let descrDiv = description[i].nextElementSibling;

                    if(descrDiv) {
                        if(description[i].value) {
                            descrDiv.textContent = `${descrCount} caractères restants`;

                            if(descrCount > 0 && descrCount < 200 || descrCount == 0) {
                                descrDiv.style.color = "green";

                                if(descrCount <= 1) {
                                    descrDiv.textContent = `${descrCount} caractère restant`;
                                }
                            }

                            else if(descrCount < 0) {
                                descrDiv.textContent = "La description ne doit pas dépasser 200 caractères, espaces comprises.";
                                descrDiv.style.color = "red";
                            }
                        }

                        else {
                            descrDiv.style.color = "inherit";
                            descrDiv.textContent = "200 caractères restants";
                        }
                    }
                });
            }
        }
    }

//*****END OF THE CLASS*****//
}