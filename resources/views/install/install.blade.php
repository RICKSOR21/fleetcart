@extends('install.layout')

@section('content')
    @include('install.requirements')
    @include('install.permissions')
    @include('install.configuration')
    @include('install.complete')

    <template x-if="!appInstalled">
        <footer class="footer d-flex justify-content-end">
            <template x-if="isShowPrev">
                <button
                    type="button"
                    class="btn btn-light"
                    :disabled="isPrevDisabled"
                    @click="prevStep"
                >
                    Back
                </button>
            </template>

            <template x-if="step !== 4">
                <button
                    type="button"
                    class="btn btn-primary"
                    :class="{ 'btn-loading': formSubmitting }"
                    :disabled="isNextDisabled"
                    @click="nextStep"
                    x-text="step === 3 ? 'Install' : 'Next'"
                >
                </button>
            </template>
        </footer>
    </template>
@endsection
