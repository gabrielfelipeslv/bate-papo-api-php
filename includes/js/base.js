class TargetNodes extends Array{
    constructor(selector) {
        super()
        if(selector.charAt(0) == "#"){
            Object.assign(this, document.querySelectorAll("[id='" + selector.substr(1) + "']"))
        } else {
            Object.assign(this, document.querySelectorAll(selector))
        }
    }
    css(styleName, value = ''){
        this.forEach(function(x){
            x.style[styleName] = value;
        })
        return this;
    }
    class(className, wantToRemoveClass = false){
        this.forEach(function(x){
            if(wantToRemoveClass){
                x.classList.remove(className);
            } else {
                x.classList.add(className);
            }
        })
        return this;
    }
    toggleClass(className){
        this.forEach(function(x){
            let list = x.classList;
            if(list.contains(className)){
                list.remove(className)
            } else {
                list.add(className)
            }
        })
        return this;
    }
    attr(attrName, newValue){
        this.forEach(function(x){
            if(newValue != null){
                x.setAttribute(attrName, newValue)
            } else {
                return x.getAttribute(attrName)
            }
        })
        return this;
    }
    setHtml(newValue){
        this.forEach(function(x){
            if(newValue != null){
                x.innerHTML = newValue;
            } else {
                return x.innerHTML;
            }
        })
        return this;
    }
    toggleAttr(attrName, newValue = ''){
        this.forEach(function(x){
            if(x.hasAttribute(attrName)){
                x.removeAttribute(attrName)
            } else {
                x.setAttribute(attrName, newValue);
            }
        })
        return this;
    }
    val(){
        return this[0].value
    }
    focus(){
        this[0].focus()
    }
    scrollTo(){
        const posicao = this[0].getBoundingClientRect().top;
        if(posicao <= 0){
            window.scrollTo(0, -1*document.querySelector('html').getBoundingClientRect().top - (-1*posicao) - $$('#menu-principal-teuquiz')[0].getBoundingClientRect().height - 10);
        } else {
            window.scrollTo(0, -1*document.querySelector('html').getBoundingClientRect().top + posicao - $$('#menu-principal-teuquiz')[0].getBoundingClientRect().height - 10);
        }
    }
    random(){
        return this[Math.floor(Math.random() * this.length)];
    }
    fadeOut(){
        this.forEach(function(x){
            x.style.opacity = 0
            setTimeout(function() {
                x.style.display = "none"
            }, 1000)
        })
    }
    fadeIn(){
        this.forEach(function(x){
            x.style.display = "block"
            x.style.transition = "opacity 1000ms"
            x.style.opacity = 0
            setTimeout(function() {
                x.style.opacity = 1
            }, 500)
        })
        return this;
    }
    fadeOut(){
        this.forEach(function(x){
            x.style.transition = "opacity 1000ms"
            x.style.opacity = '0';
            setTimeout(function() {
                x.style.display = "none"
            }, 1010)
        })
        return this;
    }
}

function $$(selector){
    return new TargetNodes(selector);
}

function troca_div(x, y){
    $$(x).fadeOut();
    $$(y).fadeIn();
    setTimeout(() => $$(y).scrollTo(), 1500);
}

