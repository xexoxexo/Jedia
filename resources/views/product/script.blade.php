<script>
    $(document).ready(function () {
        let nextPageUrl = '{{ $products->nextPageUrl() }}';
        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                if (nextPageUrl) {
                    loadMoreProducts();
                }
            }
        });

        function loadMoreProducts() {
            $.ajax({
                url: nextPageUrl,
                type: 'get',
                beforeSend: function () {
                    nextPageUrl = '';
                },
                success: function (data) {
                    nextPageUrl = data.nextPageUrl;
                    $('#products-container').append(data.view);
                },
                error: function (xhr, status, error) {
                    console.log('Error loading more products', error);
                }
            });
        }
    });
</script>
