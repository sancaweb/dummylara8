(function ($) {
    $.fn.filemanager = function (type, options) {
        type = type || "file";

        this.on("click", function (e) {
            var route_prefix =
                options && options.prefix ? options.prefix : "/filemanager";
            var target_input = $("#" + $(this).data("input"));
            var target_preview = $("#" + $(this).data("preview"));
            var linkfoto = $("#" + $(this).data("linkfoto"));
            var thumb = $("#" + $(this).data("thumb"));

            window.open(
                route_prefix + "?type=" + type,
                "FileManager",
                "width=900,height=600"
            );

            window.SetUrl = function (items) {
                var file_path = items
                    .map(function (item) {
                        return item.url;
                    })
                    .join(",");

                var thumb_path = items
                    .map(function (item) {
                        return item.thumb_url;
                    })
                    .join(",");

                // set the value of the desired input to image url
                target_input.val("").val(file_path).trigger("change");
                thumb.val("").val(thumb_path).trigger("change");

                // clear previous preview
                // target_preview.html("");
                target_preview.attr("src", base_url + "/images/no-image.png");
                linkfoto.attr("href", base_url + "/images/no-image.png");

                // set or change the preview image src
                items.forEach(function (item) {
                    // target_preview.append(
                    //     $("<img>")
                    //         .css("height", "5rem")
                    //         .attr("src", item.thumb_url)
                    // );
                    target_preview.attr("src", item.thumb_url);
                    linkfoto.attr("href", item.url);
                });

                // trigger change event
                target_preview.trigger("change");
            };
            return false;
        });
    };
})(jQuery);
