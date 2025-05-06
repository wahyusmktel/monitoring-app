<form action="{{ route('server.destroy', $row->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm text-white" style="background: linear-gradient(45deg, #a10029, #f13f20);"
        onclick="return confirm('Yakin ingin menghapus data ini?')">
        <i class="fas fa-trash"></i>
    </button>
</form>
