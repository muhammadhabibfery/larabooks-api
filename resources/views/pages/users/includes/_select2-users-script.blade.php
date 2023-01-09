<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
            $(".city").select2({
            placeholder: "Select City",
            allowClear: true,
            });
            $(".categories-multiple").select2({
            placeholder: "Select Categories",
            allowClear: true,
            });
        });

</script>
