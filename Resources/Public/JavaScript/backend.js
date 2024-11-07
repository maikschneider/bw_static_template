export default class BwStaticTemplatePreview {

  constructor() {
    document.querySelectorAll('button[data-json-table]').forEach((button) => {
      button.addEventListener('click', this.displayFullTable)
    })
  }

  displayFullTable(event) {
    event.preventDefault()
    const target = event.currentTarget.getAttribute('data-json-table')
    document.querySelector(`#${target}`).classList.remove('jsonTablePreview--hidden')
  }
}
