<!-- Aksi Verifikasi -->
<div class="mt-4 border-top pt-4">
    <h6 class="text-muted">Aksi Verifikasi</h6>

    <form action="{{ route('verifikasi.approve', $tutor->id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-success btn-lg px-4">
            ✅ Approve Tutor
        </button>
    </form>

    <button type="button" class="btn btn-danger btn-lg px-4"
            data-bs-toggle="modal" data-bs-target="#rejectModal">
        ❌ Tolak Tutor
    </button>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('verifikasi.reject', $tutor->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Tutor</h5>
                </div>
                <div class="modal-body">
                    <textarea name="reason" class="form-control" rows="4"
                        placeholder="Alasan penolakan..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Tutor</button>
                </div>
            </form>
        </div>
    </div>
</div>
