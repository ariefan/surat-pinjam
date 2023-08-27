<?= $this->extend('layout/app') ?>

<?= $this->section('css') ?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div id="editor">
    <p>Hello World!</p>
    <p>Some initial <strong>bold</strong> text</p>
    <p><br></p>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#editor', {
        modules: {
            toolbar: [
                [{
                    header: [1, 2, false]
                }],
                ['bold', 'italic', 'underline'],
                ['image', 'code-block']
            ]
        },
        placeholder: 'Compose an epic...',
        theme: 'snow'
    });
</script>
<?= $this->endSection() ?>