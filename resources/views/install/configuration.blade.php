<template x-if="step !== 1 && step !== 2 && step === 3 && !appInstalled">
    <div class="has-scrollable-content configuration d-flex flex-column">
        <div class="header overflow-hidden">
            <h3>Configuration</h3>
            
            <p class="excerpt">Please configure your application with necessary information and credentials.</p>
        </div>

        <div class="content position-relative flex-grow-1 overflow-hidden">
            <div class="scrollable-content" x-ref="configurationContent">
                <template x-if="hasErrorMessage">
                    <div
                        class="alert alert-danger alert-dismissible show fade"
                        :class="{
                            'animate__animated animate__headShake': animateAlert
                        }"
                        role="alert"
                    >
                        <span x-html="errorMessage"></span>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"
                            @click="resetErrorMessage"
                        >
                        </button>
                    </div>
                </template>

                <form
                    class="configuration-form form-horizontal"
                    @input="errors.clear($event.target.name)"
                    x-ref="configurationForm"
                >
                    <div class="box overflow-hidden">
                        <div class="title">
                            <h5>Database</h5>
                        </div>

                        <div class="row form-group">
                            <label for="db_host" class="col-md-3 col-form-label">
                                Host <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="text"
                                    autocomplete="off"
                                    name="db_host"
                                    id="db_host"
                                    class="form-control"
                                    x-model="form.db_host"
                                >

                                <template x-if="errors.has('db_host')">
                                    <span class="invalid-feedback" x-text="errors.get('db_host')"></span>
                                </template>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="db_port" class="col-md-3 col-form-label">
                                Port <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="text"
                                    autocomplete="off"
                                    name="db_port"
                                    id="db_port"
                                    class="form-control"
                                    x-model="form.db_port"
                                >

                                <template x-if="errors.has('db_port')">
                                    <span class="invalid-feedback" x-text="errors.get('db_port')"></span>
                                </template>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="db_username" class="col-md-3 col-form-label">
                                Username <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="text"
                                    autocomplete="off"
                                    name="db_username"
                                    id="db_username"
                                    class="form-control"
                                    x-model="form.db_username"
                                >

                                <template x-if="errors.has('db_username')">
                                    <span class="invalid-feedback" x-text="errors.get('db_username')"></span>
                                </template>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="db_password" class="col-md-3 col-form-label">
                                Password
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="password"
                                    autocomplete="off"
                                    name="db_password"
                                    id="db_password"
                                    class="form-control"
                                    x-model="form.db_password"
                                >

                                <template x-if="errors.has('db_password')">
                                    <span class="invalid-feedback" x-text="errors.get('db_password')"></span>
                                </template>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="db_database" class="col-md-3 col-form-label">
                                Database <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="text"
                                    autocomplete="off"
                                    name="db_database"
                                    id="db_database"
                                    class="form-control"
                                    x-model="form.db_database"
                                >

                                <template x-if="errors.has('db_database')">
                                    <span class="invalid-feedback" x-text="errors.get('db_database')"></span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="box overflow-hidden">
                        <div class="title">
                            <h5>Admin</h5>
                        </div>

                        <div class="row form-group">
                            <label for="admin_first_name" class="col-md-3 col-form-label">
                                First Name <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="text"
                                    autocomplete="off"
                                    name="admin_first_name"
                                    id="admin_first_name"
                                    class="form-control"
                                    x-model="form.admin_first_name"
                                >

                                <template x-if="errors.has('admin_first_name')">
                                    <span class="invalid-feedback" x-text="errors.get('admin_first_name')"></span>
                                </template>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="admin_last_name" class="col-md-3 col-form-label">
                                Last Name <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="text"
                                    autocomplete="off"
                                    name="admin_last_name"
                                    id="admin_last_name"
                                    class="form-control"
                                    x-model="form.admin_last_name"
                                >

                                <template x-if="errors.has('admin_last_name')">
                                    <span class="invalid-feedback" x-text="errors.get('admin_last_name')"></span>
                                </template>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="admin_email" class="col-md-3 col-form-label">
                                Email <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="email"
                                    autocomplete="off"
                                    name="admin_email"
                                    id="admin_email"
                                    class="form-control"
                                    x-model="form.admin_email"
                                >

                                <template x-if="errors.has('admin_email')">
                                    <span class="invalid-feedback" x-text="errors.get('admin_email')"></span>
                                </template>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="admin_phone" class="col-md-3 col-form-label">
                                Phone <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="text"
                                    autocomplete="off"
                                    name="admin_phone"
                                    id="admin_phone"
                                    class="form-control"
                                    x-model="form.admin_phone"
                                >

                                <template x-if="errors.has('admin_phone')">
                                    <span class="invalid-feedback" x-text="errors.get('admin_phone')"></span>
                                </template>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="admin_password" class="col-md-3 col-form-label">
                                Password <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="password"
                                    autocomplete="off"
                                    name="admin_password"
                                    id="admin_password"
                                    class="form-control"
                                    x-model="form.admin_password"
                                >

                                <template x-if="errors.has('admin_password')">
                                    <span class="invalid-feedback" x-text="errors.get('admin_password')"></span>
                                </template>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="admin_password_confirmation" class="col-md-3 col-form-label">
                                Confirm Password <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="password"
                                    autocomplete="off"
                                    name="admin_password_confirmation"
                                    id="admin_password_confirmation"
                                    class="form-control"
                                    x-model="form.admin_password_confirmation"
                                >

                                <template x-if="errors.has('admin_password_confirmation')">
                                    <span class="invalid-feedback" x-text="errors.get('admin_password_confirmation')"></span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="box overflow-hidden">
                        <div class="title">
                            <h5>Store</h5>
                        </div>

                        <div class="row form-group">
                            <label for="store_name" class="col-md-3 col-form-label">
                                Name <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="text"
                                    autocomplete="off"
                                    name="store_name"
                                    id="store_name"
                                    class="form-control"
                                    x-model="form.store_name"
                                >

                                <template x-if="errors.has('store_name')">
                                    <span class="invalid-feedback" x-text="errors.get('store_name')"></span>
                                </template>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="store_email" class="col-md-3 col-form-label">
                                Email <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="email"
                                    autocomplete="off"
                                    name="store_email"
                                    id="store_email"
                                    class="form-control"
                                    x-model="form.store_email"
                                >

                                <template x-if="errors.has('store_email')">
                                    <span class="invalid-feedback" x-text="errors.get('store_email')"></span>
                                </template>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="store_phone" class="col-md-3 col-form-label">
                                Phone <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <input
                                    type="text"
                                    autocomplete="off"
                                    name="store_phone"
                                    id="store_phone"
                                    class="form-control"
                                    x-model="form.store_phone"
                                >

                                <template x-if="errors.has('store_phone')">
                                    <span class="invalid-feedback" x-text="errors.get('store_phone')"></span>
                                </template>
                            </div>
                        </div>

                        <div class="row form-group">
                            <label for="store_search_engine" class="col-md-3 col-form-label">
                                Search Engine <span class="required">*</span>
                            </label>

                            <div class="col-md-9">
                                <select
                                    name="store_search_engine"
                                    id="store_search_engine"
                                    class="form-select"
                                    @change="focusSearchEngineInputField($event.target.value)"
                                    x-model="form.store_search_engine"
                                >
                                    <option value="mysql">MySQL</option>
                                    <option value="algolia">Algolia</option>
                                    <option value="meilisearch">Meilisearch</option>
                                </select>

                                <template x-if="errors.has('store_search_engine')">
                                    <span class="invalid-feedback" x-text="errors.get('store_search_engine')"></span>
                                </template>

                                <template x-if="!errors.has('store_search_engine')">
                                    <span class="text-muted">You cannot change the search engine later.</span>
                                </template>
                            </div>
                        </div>

                        <template x-if="form.store_search_engine === 'algolia'">
                            <div class="row form-group">
                                <label for="algolia_app_id" class="col-md-3 col-form-label">
                                    Algolia Application ID <span class="required">*</span>
                                </label>

                                <div class="col-md-9">
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        name="algolia_app_id"
                                        id="algolia_app_id"
                                        class="form-control"
                                        x-model="form.algolia_app_id"
                                    >

                                    <template x-if="errors.has('algolia_app_id')">
                                        <span class="invalid-feedback" x-text="errors.get('algolia_app_id')"></span>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="form.store_search_engine === 'algolia'">
                            <div class="row form-group">
                                <label for="algolia_secret" class="col-md-3 col-form-label">
                                    Algolia Admin API Key <span class="required">*</span>
                                </label>

                                <div class="col-md-9">
                                    <input
                                        type="password"
                                        autocomplete="off"
                                        name="algolia_secret"
                                        id="algolia_secret"
                                        class="form-control"
                                        x-model="form.algolia_secret"
                                    >

                                    <template x-if="errors.has('algolia_secret')">
                                        <span class="invalid-feedback" x-text="errors.get('algolia_secret')"></span>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="form.store_search_engine === 'meilisearch'">
                            <div class="row form-group">
                                <label for="meilisearch_host" class="col-md-3 col-form-label">
                                    Meilisearch Host <span class="required">*</span>
                                </label>

                                <div class="col-md-9">
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        name="meilisearch_host"
                                        id="meilisearch_host"
                                        class="form-control"
                                        x-model="form.meilisearch_host"
                                    >

                                    <template x-if="errors.has('meilisearch_host')">
                                        <span class="invalid-feedback" x-text="errors.get('meilisearch_host')"></span>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="form.store_search_engine === 'meilisearch'">
                            <div class="row form-group">
                                <label for="meilisearch_key" class="col-md-3 col-form-label">
                                    Meilisearch Key <span class="required">*</span>
                                </label>

                                <div class="col-md-9">
                                    <input
                                        type="password"
                                        autocomplete="off"
                                        name="meilisearch_key"
                                        id="meilisearch_key"
                                        class="form-control"
                                        x-model="form.meilisearch_key"
                                    >

                                    <template x-if="errors.has('meilisearch_key')">
                                        <span class="invalid-feedback" x-text="errors.get('meilisearch_key')"></span>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
