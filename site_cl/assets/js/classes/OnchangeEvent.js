export default class OnchangeEvent {

    constructor() {
        this.pictureRegex = /^(?<!\s)[\w\-_](?!\s)[^.]*/
        this.extensionRegex = /\.(jpe?g|png)$/i
    }

    pictureMessages() {
        const cover = document.getElementById("album-cover")
        const pictures = document.getElementById("album-pictures")
        const newPicture = document.querySelectorAll("#new-picture")
        const pictureNames = document.querySelectorAll("#current-name")
        const extraPictures = document.getElementById("extra-pictures")
        
        const allowedCovsize = 3145728
        const allowedPicsize = 31457280
        let picSize = 0
        let extraPicSize = 0

        if(cover) {
            cover.addEventListener("change", () => {
                const covDiv = cover.nextElementSibling
                const sizeDiv = cover.nextElementSibling.nextElementSibling
                covDiv.textContent = ""

                if(covDiv && sizeDiv) {
                    if(cover.files.length > 0) {
                        let covSize = cover.files[0].size
                        let convertedSize = 0
                        const unit = ["octets", "Ko", "Mo", "Go", "To"]
                        const convCovSize = parseInt(Math.floor(Math.log(covSize) / Math.log(1024)), 10)
                        
                        if(convCovSize === 0) {
                            return `${covSize} ${unit[convertedSize]}`
                        }
                            
                        const covString = `${(covSize / (1024 ** convCovSize)).toFixed(2)} ${unit[convCovSize]}`
                        const trueCovString = covString.replace(".", ",")

                        if(covSize > allowedCovsize) {
                            sizeDiv.textContent = "La couverture ne doit pas dépasser 3 Mo."
                            sizeDiv.style.color = "red"
                            covDiv.textContent = ""
                        }

                        else {
                            sizeDiv.textContent = `Taille couverture : ${trueCovString}`
                            sizeDiv.style.color = "green"

                            if(this.pictureRegex.test(cover.files[0].name) && this.extensionRegex.test(cover.files[0].name) &&
                            !cover.files[0].name.includes(" ")) {
                                const image = new Image()

                                if(screen.width < 577) {
                                    image.style.maxWidth = "60%"
                                }
    
                                else {
                                    image.style.maxWidth = "40%"
                                }

                                const src = URL.createObjectURL(cover.files[0])
                                image.src = src
                                covDiv.appendChild(image)
                                image.style.display = "block"
                                image.style.margin = "auto"
                            }
                
                            else if(!this.pictureRegex.test(cover.files[0].name) || !this.extensionRegex.test(cover.files[0].name) ||
                            cover.files[0].name.includes(" ")) {
                                covDiv.textContent = "Format de fichier invalide."
                                covDiv.style.color = "red"
                                sizeDiv.style.display = "none"
                            }
                        }
                    }
                }
            });
        }

        if(pictures) {
            pictures.addEventListener("change", () => {
                const picDiv = pictures.nextElementSibling
                const sizeDiv = pictures.nextElementSibling.nextElementSibling

                picDiv.textContent = ""

                if(picDiv && sizeDiv) {
                    if(pictures.files.length > 0) {
                        for(let i = 0; i < pictures.files.length; i ++) {
                            picSize += pictures.files[i].size
                            let picString = ""
                            let truePicString = ""
                            let convertedSize = 0
                            const unit = ["octets", "Ko", "Mo", "Go", "To"]
                            const convPicSize = parseInt(Math.floor(Math.log(picSize) / Math.log(1024)), 10)
                            
                            if(convPicSize === 0) {
                                return `${picSize} ${unit[convertedSize]}`
                            }

                            picString = `${(picSize / (1024 ** convPicSize)).toFixed(2)} ${unit[convPicSize]}`
                            truePicString = picString.replace(".", ",")

                            if(picSize > allowedPicsize) {
                                sizeDiv.textContent = "Les images autres que la couverture ne doivent pas dépasser 30 Mo au total."
                                sizeDiv.style.color = "red"
                                picDiv.textContent = ""
                            }

                            else {
                                sizeDiv.textContent = `Taille totale image(s) : ${truePicString}`
                                sizeDiv.style.color = "green"

                                if(this.pictureRegex.test(pictures.files[i].name) && this.extensionRegex.test(pictures.files[i].name) && !pictures.files[i].name.includes(" ")) {
                                    const image = new Image()

                                    if(screen.width < 577) {
                                        image.style.maxWidth = "60%"
                                    }
        
                                    else {
                                        image.style.maxWidth = "40%"
                                    }

                                    const src = URL.createObjectURL(pictures.files[i])
                                    image.src = src
                                    picDiv.appendChild(image)
                                    image.style.display = "block"
                                    image.style.margin = "auto"
                                    image.style.marginTop = "1rem"
                                }
                    
                                else if(!this.pictureRegex.test(pictures.files[i].name) || !this.extensionRegex.test(pictures.files[i].name)
                                || pictures.files[i].name.includes(" ")) {
                                    const picError = document.createElement("p")
                                    picError.textContent = `Format du fichier ${pictures.files[i].name} invalide.`
                                    picError.style.color = "red"
                                    picDiv.appendChild(picError)
                                    sizeDiv.style.display = "none"
                                }
                            }
                        }
                    }
                }
            });
        }

        for(let i = 0; i < newPicture.length; i ++) {
            if(newPicture[i]) {
                newPicture[i].addEventListener("change", () => {
                    const newPic = newPicture[i].nextElementSibling
                    const sizeDiv = newPicture[i].nextElementSibling.nextElementSibling
                    const totalSizeDiv = newPicture[i].nextElementSibling.nextElementSibling.nextElementSibling

                    newPic.textContent = ""

                    if(newPic && sizeDiv && totalSizeDiv) {
                        if(newPicture[i].files.length > 0) {
                            let newPicSize = newPicture[i].files[0].size
                            let totalCurrentSize = 0
                            let newPicString = ""
                            let trueNewPicString = ""
                            let currentPicString = ""
                            let trueCurrentPicString = ""
                            let convertedSize = 0
                            let currentPicSize = 0
                            const unit = ["octets", "Ko", "Mo", "Go", "To"]
                            const convNewPicSize = parseInt(Math.floor(Math.log(newPicSize) / Math.log(1024)), 10)
                            
                            if(convNewPicSize === 0) {
                                return `${newPicSize} ${unit[convertedSize]}`
                            }
                                
                            newPicString = `${(newPicSize / (1024 ** convNewPicSize)).toFixed(2)} ${unit[convNewPicSize]}`
                            trueNewPicString = newPicString.replace(".", ",")
                                    
                            for(let idx = 0; idx < pictureNames.length; idx ++) {
                                currentPicSize = pictureNames[idx].src.length
                                convertedSize = parseInt(Math.floor(Math.log(currentPicSize) / Math.log(1024)), 10)
                                    
                                if(convertedSize === 0) {
                                    return `${currentPicSize} ${unit[convertedSize]}`
                                }
                                
                                totalCurrentSize += currentPicSize
                            }
                            
                            let totalPicSize = totalCurrentSize + newPicSize
                            const convTotalPicSize = parseInt(Math.floor(Math.log(totalPicSize) / Math.log(1024)), 10)

                            currentPicString = `${(totalPicSize / (1024 ** convTotalPicSize)).toFixed(2)} ${unit[convTotalPicSize]}`
                            trueCurrentPicString = currentPicString.replace(".", ",")

                            if(totalPicSize > allowedPicsize) {
                                totalSizeDiv.textContent = "Le total des images existantes et remplacées ne doit pas dépasser 30 Mo."
                                totalSizeDiv.style.color = "red"
                                sizeDiv.textContent = ""
                            }

                            else {
                                totalSizeDiv.textContent = `Taille totale images existantes et remplacées : ${trueCurrentPicString}`
                                totalSizeDiv.style.color = "green"

                                if(this.pictureRegex.test(newPicture[i].files[0].name) &&
                                this.extensionRegex.test(newPicture[i].files[0].name) && !newPicture[i].files[0].name.includes(" ")) {
                                    newPic.textContent = ""
                                    const image = new Image()

                                    if(screen.width < 769) {
                                        image.style.maxWidth = "60%"
                                    }
        
                                    else {
                                        image.style.maxWidth = "100%"
                                    }

                                    const src = URL.createObjectURL(newPicture[i].files[0])
                                    image.src = src
                                    newPic.appendChild(image)
                                    image.style.display = "block"
                                    image.style.margin = "auto"
                                    sizeDiv.textContent = `Taille nouvelle image : ${trueNewPicString}`
                                }

                                else if(!this.pictureRegex.test(newPicture[i].files[0].name) ||
                                !this.extensionRegex.test(newPicture[i].files[0].name) || newPicture[i].files[0].name.includes(" ")) {
                                    newPic.textContent = "Format de fichier invalide."
                                    newPic.style.color = "red"
                                    sizeDiv.style.display = "none"
                                    totalSizeDiv.style.display = "none"
                                }
                            }
                        }
                    }
                });
            }
        }

        if(extraPictures) {
            extraPictures.addEventListener("change", () => {
                const extraPicdiv = extraPictures.nextElementSibling
                const totalSizeDiv = extraPictures.nextElementSibling.nextElementSibling

                extraPicdiv.textContent = ""

                if(extraPicdiv && totalSizeDiv) {
                    if(extraPictures.files.length > 0) {
                        for(let i = 0; i < extraPictures.files.length; i ++) {
                            extraPicSize += extraPictures.files[i].size
                            let totalCurrentSize = 0
                            let extraPicString = ""
                            let trueExtraPicString = ""
                            let convertedSize = 0
                            let currentPicSize = 0
                            const unit = ["octets", "Ko", "Mo", "Go", "To"]
                            const convExtraPicSize = parseInt(Math.floor(Math.log(extraPicSize) / Math.log(1024)), 10)
                            
                            if(convExtraPicSize === 0) {
                                return `${extraPicSize} ${unit[convertedSize]}`
                            }
                                    
                            for(let idx = 0; idx < pictureNames.length; idx ++) {
                                currentPicSize = pictureNames[idx].src.length
                                convertedSize = parseInt(Math.floor(Math.log(currentPicSize) / Math.log(1024)), 10)
                                    
                                if(convertedSize === 0) {
                                    return `${currentPicSize} ${unit[convertedSize]}`
                                }
                                
                                totalCurrentSize += currentPicSize
                            }
                            
                            let totalPicSize = totalCurrentSize + extraPicSize
                            const convTotalPicSize = parseInt(Math.floor(Math.log(totalPicSize) / Math.log(1024)), 10)

                            extraPicString = `${(totalPicSize / (1024 ** convTotalPicSize)).toFixed(2)} ${unit[convTotalPicSize]}`
                            trueExtraPicString = extraPicString.replace(".", ",")

                            if(totalPicSize > allowedPicsize) {
                                totalSizeDiv.textContent = "Le total des images existantes et ajoutées ne doit pas dépasser 30 Mo."
                                totalSizeDiv.style.color = "red"
                                extraPicdiv.textContent = ""
                            }

                            else {
                                totalSizeDiv.textContent = `Taille totale images existantes et supplémentaire(s) : ${trueExtraPicString}`
                                totalSizeDiv.style.color = "green"

                                if(this.pictureRegex.test(extraPictures.files[i].name) && this.extensionRegex.test(extraPictures.files[i].name) && !extraPictures.files[i].name.includes(" ") && picSize <= allowedPicsize) {
                                    const image = new Image()

                                    if(screen.width < 577) {
                                        image.style.maxWidth = "60%"
                                    }
        
                                    else {
                                        image.style.maxWidth = "40%"
                                    }

                                    const src = URL.createObjectURL(extraPictures.files[i])
                                    image.src = src
                                    extraPicdiv.appendChild(image)
                                    image.style.display = "block"
                                    image.style.margin = "auto"
                                    image.style.marginTop = "1rem"
                                }
                    
                                else if(!this.pictureRegex.test(extraPictures.files[i].name) ||
                                !this.extensionRegex.test(extraPictures.files[i].name) || extraPictures.files[i].name.includes(" ")) {
                                    const picError = document.createElement("p")
                                    picError.textContent = `Format du fichier ${extraPictures.files[i].name} invalide.`
                                    picError.style.color = "red"
                                    extraPicdiv.appendChild(picError)
                                    totalSizeDiv.style.display = "none"
                                }
                            }
                        }
                    }
                }
            });
        }
    }

//*****END OF THE CLASS*****//
}