const btnAddParams = document.getElementById("add_params")
const numberOfParams = document.getElementById("num_of_params")
const paramsContainer = document.getElementById("params_container")

const createNParams = (n) => {
    const params =  []
    const col = Math.trunc(12/(Number(n)+1))

    for (let index = 0; index < Number(n)+1; index++) {
        const div = document.createElement("div")
        div.classList.add(`col-md-${col}`)

        const input = document.createElement("input")
        input.type="text"
        input.classList.add("form-control", "mt-2")

        if(index == Number(n)) {
            input.name = `return[]`
            input.placeholder = `Return Value`
        } else {
            input.name = `input${index+1}[]`
            input.placeholder = `Param ${index+1}`
        }

        div.append(input)
        
        params.push(div)
    }

    return params
}

btnAddParams.addEventListener("click", (e) => {
    e.preventDefault()
    numberOfParams.disable = true

    paramsContainer.append(...createNParams(numberOfParams.value), document.createElement("br"))
})