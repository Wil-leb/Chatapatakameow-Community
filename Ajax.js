export default class Ajax {

    like() {
        const xhr = new XMLHttpRequest()

        xhr.open("POST", document.querySelector(".vote-logos form").getAttribute("action"))
        
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest")

        const data = document.querySelector(".vote-logos form:nth-child(1) input:nth-child(2)").getAttribute("value")

        xhr.onreadystatechange = function () {
            if(xhr.readyState == 4 && xhr.status >= 200 && xhr.status < 300) {
                if(xhr.responseText == "true") {
                    const dislikeButton = document.getElementsByClassName("dislike")

                    for(let i= 0; i < dislikeButton.length; i ++) {
                        dislikeButton[i].classList.remove("is-disliked")
                    }

                    const likeButton = document.getElementsByClassName("like")

                    for(let i= 0; i < likeButton.length; i ++) {
                        likeButton[i].classList.add("is-liked")
                    }
                }
            }
        };
        console.log(data)
        xhr.send(data)
    }

    dislike() {
        const xhr = new XMLHttpRequest()

        xhr.open("POST", document.querySelector(".vote-logos form").getAttribute("action"))
        
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest")

        const data = document.querySelector(".vote-logos form:nth-child(2) input:nth-child(2)").getAttribute("value")

        xhr.onreadystatechange = function() {
            if(xhr.readyState == 4 && xhr.status >= 200 && xhr.status < 300) {
                if(xhr.responseText == "true") {
                    const likeButton = document.getElementsByClassName("like")

                    for(let i= 0; i < likeButton.length; i ++) {
                        likeButton[i].classList.remove("is-liked")
                    }

                    const dislikeButton = document.getElementsByClassName("dislike")

                    for(let i= 0; i < dislikeButton.length; i ++) {
                        dislikeButton[i].classList.add("is-disliked")
                    }
                }
            }
        };
        console.log(data)
        xhr.send(data)
    }

//*****END OF THE CLASS*****//
}