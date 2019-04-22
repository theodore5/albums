/* Θέτει την τιμή του πεδίου obj σε 1  */
function HandleFieldChange(obj) 
{
    document.getElementById(obj).value = "1";
}

/* Θέτει την τιμή του πεδίου obj σε 0  */
function ResetFieldChange(obj) 
{
    document.getElementById(obj).value = "0";
}

/* Ελέγχει την τιμή του πεδίου obj  και αν είναι 1         */
/* παράγει μήνυμα επιβεβαίωσης απομάκρυνσης απ' τη σελίδα  */
function closeIt() 
{
    if (document.getElementById('UnsavedChanges').value == "1") 
    {
        var _message = "Υπάρχουν αλλαγές που δεν έχουν αποθηκευτεί.\n\nΕπιλέξτε την ενέργειά σας:\n\n'Έξοδος απ' τη σελίδα' για κλείσιμο της σελίδας χωρίς αποθήκευση των δεδομένων.\n'Παραμονή στη σελίδα' για να συνεχίσετε την επεξεργασία.";
    }
    return _message;
};

window.onbeforeunload = closeIt;

function collapseElement(obj) {
    var el = document.getElementById(obj);
    el.style.display = 'none';
}
function expandElement(obj) {
    var el = document.getElementById(obj);
    el.style.display = 'block';
}

function action_confirm(prompt)
{
    var del=confirm(prompt);
    return del;
}