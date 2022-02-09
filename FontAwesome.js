export default class FontAwesome {

//*****Browsing arrows*****//
    createArrows() {
        const firstSection = document.querySelector("section:first-of-type")
        const lastSection = document.querySelector("section:last-of-type")
        
        const pageTop = document.createElement("div")
        const pageBottom = document.createElement("div")
        pageTop.setAttribute("id", "top")
        pageBottom.setAttribute("id", "bottom")
        
        const pageDown = document.createElement("a")
        const pageUp = document.createElement("a")
        pageDown.href = "#bottom"
        pageUp.href = "#top"
        
        const arrowDown = document.createElement("i")
        const arrowUp = document.createElement("i")
        
        arrowDown.setAttribute("class", "fas fa-arrow-circle-down")
        arrowDown.style.display = "block"
        arrowDown.style.textAlign = "center"
        arrowDown.style.fontSize = "3rem"
        
        arrowUp.setAttribute("class", "fas fa-arrow-circle-up")
        arrowUp.style.display = "block"
        arrowUp.style.textAlign = "center"
        arrowUp.style.fontSize = "3rem"
        arrowUp.style.marginTop = "2rem"
        
        pageDown.append(arrowDown)
        pageUp.append(arrowUp)
        
        firstSection.prepend(pageTop, pageDown)
        lastSection.append(pageBottom, pageUp)
        firstSection.style.marginTop = "2rem"
        lastSection.style.marginBottom = "2rem" 
    }
    
//*****END OF THE CLASS*****//   
}