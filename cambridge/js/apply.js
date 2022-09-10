document.getElementById("reason").addEventListener("change", function(e) {
    let other_reason = document.getElementById("other-reason");
    if (e.target.value === '19')
        other_reason.classList.remove('hide');
    else
        other_reason.classList.add('hide');
});