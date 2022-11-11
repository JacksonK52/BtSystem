$(function () {
    // Data Grid
    let = fname = "Data Export - ";
    $("#example1").DataTable({
        "responsive": true, "lengthChange": true, "autoWidth": false,
        "buttons": [
        "copy", 
        {
            extend: 'csvHtml5',
            title: `${fname} Html`,
        }, 
        {
            extend: 'excelHtml5',
            title: `${fname} Excel`,
        }, 
        {
            extend: 'pdfHtml5',
            title: `${fname} Pdf`
        },
        "print", 
        "colvis"
        ]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});