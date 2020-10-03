$(document).ready(function () {
    var table = $('#datatable').DataTable({
        /* scrollY: 500,
        paging: false, */ // pour mettre une scroll bar à la place
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        buttons: ['copy', 'excel', 'pdf', 'colvis'],
        "language": { // traduction en français de la table
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
            "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Afficher _MENU_ éléments",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "0": "Aucune ligne sélectionnée",
                    "1": "1 ligne sélectionnée"
                }
            }
        },
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true
    });

    table.buttons().container()
        .appendTo('#datatable_wrapper .col-md-6:eq(0)');


});
