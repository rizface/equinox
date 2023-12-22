
function deleteParams(e) {
    const row = e.dataset.row
    const paramRow = document.getElementById(`row${row}`)

    paramRow.remove()
}

function updateParams(e) {
    const row = e.dataset.row
    const params = document.querySelectorAll(`#input${row}`)

    params.forEach(p => p.disabled=false)

    document.getElementById(`save-params-${row}`).style.opacity= 100
}

function saveParams(e) {
    const row = e.dataset.row
    const params = document.querySelectorAll(`#input${row}`)

    params.forEach(p => p.disabled=true)

    document.getElementById(`save-params-${row}`).style.opacity= 0
}