<div id="confirmationModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="modalContent"></p>
      </div>
      <div class="modal-footer">
        <?= form_open('', ['method' => 'POST', 'id' => 'confirmationForm']); ?>
          <?= form_submit('submit', 'Delete', ['class' => 'btn btn-danger']); ?>
        <?= form_close(); ?>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>