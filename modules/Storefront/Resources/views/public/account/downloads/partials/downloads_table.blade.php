<div class="table-responsive">
    <table class="table table-borderless my-downloads-table">
        <thead>
            <tr>
                <th>{{ trans('storefront::account.downloads.filename') }}</th>
                <th>{{ trans('storefront::account.action') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($downloads as $download)
                <tr>
                    <td>
                        {{ $download->filename }}
                    </td>

                    <td>
                        <a href="{{ route('account.downloads.show', encrypt($download->id)) }}" title="{{ trans('storefront::account.downloads.download') }}" class="btn btn-download">
                            <i class="las la-cloud-download-alt"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
