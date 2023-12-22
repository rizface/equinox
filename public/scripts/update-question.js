function deleteParams(e) {
    const row = e.dataset.row
    const paramRow = document.getElementById(`row${row}`)

    paramRow.remove()
}