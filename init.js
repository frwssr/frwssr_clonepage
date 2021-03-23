function process(path, renamepostfix) {
    var form = document.getElementById('editattr'),
        renamepostfix = typeof renamepostfix != "undefined" ? '&renamepostfix=' + renamepostfix : '';
    
    querystring = form.action.split('?')[1];

    form.action = path + '?' + querystring + renamepostfix;
    form.submit();
}
document.querySelector('.frwssr_clonepage__button').onclick = function() {
    process(this.dataset.path, this.dataset.renamepostfix);
    return false;
};
