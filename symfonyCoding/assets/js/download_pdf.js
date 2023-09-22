document.addEventListener('DOMContentLoaded', function() {
    const downloadLinks = document.querySelectorAll('.download-link');

    downloadLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();

            const articleId = this.dataset.articleId;

            // Appel à l'action de téléchargement PDF avec l'ID de l'article
            window.location.href = `/download-article-pdf/${articleId}`;
        });
    });
});