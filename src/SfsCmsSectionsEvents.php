<?php

namespace Softspring\CmsSectionsPlugin;

class SfsCmsSectionsEvents
{
    // SECTION LIST EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTIONS_LIST_INITIALIZE = 'sfs_cms.admin.sections.list_initialize';
    public const ADMIN_SECTIONS_LIST_FILTER_FORM_PREPARE = 'sfs_cms.admin.sections.filter_form_prepare';
    public const ADMIN_SECTIONS_LIST_FILTER_FORM_INIT = 'sfs_cms.admin.sections.filter_form_init';
    public const ADMIN_SECTIONS_LIST_FILTER = 'sfs_cms.admin.sections.list_filter';
    public const ADMIN_SECTIONS_LIST_VIEW = 'sfs_cms.admin.sections.list_view';
    public const ADMIN_SECTIONS_LIST_EXCEPTION = 'sfs_cms.admin.sections.list_exception';
    // SECTION CREATE EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTIONS_CREATE_INITIALIZE = 'sfs_cms.admin.sections.create_initialize';
    public const ADMIN_SECTIONS_CREATE_ENTITY = 'sfs_cms.admin.sections.create_create_entity';
    public const ADMIN_SECTIONS_CREATE_FORM_PREPARE = 'sfs_cms.admin.sections.create_form_prepare';
    public const ADMIN_SECTIONS_CREATE_FORM_INIT = 'sfs_cms.admin.sections.create_form_init';
    public const ADMIN_SECTIONS_CREATE_FORM_VALID = 'sfs_cms.admin.sections.create_form_valid';
    public const ADMIN_SECTIONS_CREATE_APPLY = 'sfs_cms.admin.sections.create_apply';
    public const ADMIN_SECTIONS_CREATE_SUCCESS = 'sfs_cms.admin.sections.create_success';
    public const ADMIN_SECTIONS_CREATE_FAILURE = 'sfs_cms.admin.sections.create_failure';
    public const ADMIN_SECTIONS_CREATE_FORM_INVALID = 'sfs_cms.admin.sections.create_form_invalid';
    public const ADMIN_SECTIONS_CREATE_VIEW = 'sfs_cms.admin.sections.create_view';
    public const ADMIN_SECTIONS_CREATE_EXCEPTION = 'sfs_cms.admin.sections.create_exception';
    // SECTION IMPORT EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTIONS_IMPORT_INITIALIZE = 'sfs_cms.admin.sections.import_initialize';
    public const ADMIN_SECTIONS_IMPORT_ENTITY = 'sfs_cms.admin.sections.import_import_entity';
    public const ADMIN_SECTIONS_IMPORT_FORM_PREPARE = 'sfs_cms.admin.sections.import_form_prepare';
    public const ADMIN_SECTIONS_IMPORT_FORM_INIT = 'sfs_cms.admin.sections.import_form_init';
    public const ADMIN_SECTIONS_IMPORT_FORM_VALID = 'sfs_cms.admin.sections.import_form_valid';
    public const ADMIN_SECTIONS_IMPORT_APPLY = 'sfs_cms.admin.sections.import_apply';
    public const ADMIN_SECTIONS_IMPORT_SUCCESS = 'sfs_cms.admin.sections.import_success';
    public const ADMIN_SECTIONS_IMPORT_FAILURE = 'sfs_cms.admin.sections.import_failure';
    public const ADMIN_SECTIONS_IMPORT_FORM_INVALID = 'sfs_cms.admin.sections.import_form_invalid';
    public const ADMIN_SECTIONS_IMPORT_VIEW = 'sfs_cms.admin.sections.import_view';
    public const ADMIN_SECTIONS_IMPORT_EXCEPTION = 'sfs_cms.admin.sections.import_exception';
    // SECTION READ EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTIONS_READ_INITIALIZE = 'sfs_cms.admin.sections.read_initialize';
    public const ADMIN_SECTIONS_READ_LOAD_ENTITY = 'sfs_cms.admin.sections.read_load_entity';
    public const ADMIN_SECTIONS_READ_NOT_FOUND = 'sfs_cms.admin.sections.read_not_found';
    public const ADMIN_SECTIONS_READ_FOUND = 'sfs_cms.admin.sections.read_found';
    public const ADMIN_SECTIONS_READ_VIEW = 'sfs_cms.admin.sections.read_view';
    public const ADMIN_SECTIONS_READ_EXCEPTION = 'sfs_cms.admin.sections.read_exception';
    // SECTION UPDATE EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTIONS_UPDATE_INITIALIZE = 'sfs_cms.admin.sections.update_initialize';
    public const ADMIN_SECTIONS_UPDATE_LOAD_ENTITY = 'sfs_cms.admin.sections.update_load_entity';
    public const ADMIN_SECTIONS_UPDATE_NOT_FOUND = 'sfs_cms.admin.sections.update_not_found';
    public const ADMIN_SECTIONS_UPDATE_FOUND = 'sfs_cms.admin.sections.update_found';
    public const ADMIN_SECTIONS_UPDATE_FORM_PREPARE = 'sfs_cms.admin.sections.update_form_prepare';
    public const ADMIN_SECTIONS_UPDATE_FORM_INIT = 'sfs_cms.admin.sections.update_form_init';
    public const ADMIN_SECTIONS_UPDATE_FORM_VALID = 'sfs_cms.admin.sections.update_form_valid';
    public const ADMIN_SECTIONS_UPDATE_APPLY = 'sfs_cms.admin.sections.update_apply';
    public const ADMIN_SECTIONS_UPDATE_SUCCESS = 'sfs_cms.admin.sections.update_success';
    public const ADMIN_SECTIONS_UPDATE_FAILURE = 'sfs_cms.admin.sections.update_failure';
    public const ADMIN_SECTIONS_UPDATE_FORM_INVALID = 'sfs_cms.admin.sections.update_form_invalid';
    public const ADMIN_SECTIONS_UPDATE_VIEW = 'sfs_cms.admin.sections.update_view';
    public const ADMIN_SECTIONS_UPDATE_EXCEPTION = 'sfs_cms.admin.sections.update_exception';
    // SECTION DUPLICATE EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTIONS_DUPLICATE_INITIALIZE = 'sfs_cms.admin.sections.duplicate_initialize';
    public const ADMIN_SECTIONS_DUPLICATE_LOAD_ENTITY = 'sfs_cms.admin.sections.duplicate_load_entity';
    public const ADMIN_SECTIONS_DUPLICATE_NOT_FOUND = 'sfs_cms.admin.sections.duplicate_not_found';
    public const ADMIN_SECTIONS_DUPLICATE_FOUND = 'sfs_cms.admin.sections.duplicate_found';
    public const ADMIN_SECTIONS_DUPLICATE_FORM_PREPARE = 'sfs_cms.admin.sections.duplicate_form_prepare';
    public const ADMIN_SECTIONS_DUPLICATE_FORM_INIT = 'sfs_cms.admin.sections.duplicate_form_init';
    public const ADMIN_SECTIONS_DUPLICATE_FORM_VALID = 'sfs_cms.admin.sections.duplicate_form_valid';
    public const ADMIN_SECTIONS_DUPLICATE_APPLY = 'sfs_cms.admin.sections.duplicate_apply';
    public const ADMIN_SECTIONS_DUPLICATE_SUCCESS = 'sfs_cms.admin.sections.duplicate_success';
    public const ADMIN_SECTIONS_DUPLICATE_FAILURE = 'sfs_cms.admin.sections.duplicate_failure';
    public const ADMIN_SECTIONS_DUPLICATE_FORM_INVALID = 'sfs_cms.admin.sections.duplicate_form_invalid';
    public const ADMIN_SECTIONS_DUPLICATE_VIEW = 'sfs_cms.admin.sections.duplicate_view';
    public const ADMIN_SECTIONS_DUPLICATE_EXCEPTION = 'sfs_cms.admin.sections.duplicate_exception';
    // SECTION DELETE EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTIONS_DELETE_INITIALIZE = 'sfs_cms.admin.sections.delete_initialize';
    public const ADMIN_SECTIONS_DELETE_LOAD_ENTITY = 'sfs_cms.admin.sections.delete_load_entity';
    public const ADMIN_SECTIONS_DELETE_NOT_FOUND = 'sfs_cms.admin.sections.delete_not_found';
    public const ADMIN_SECTIONS_DELETE_FOUND = 'sfs_cms.admin.sections.delete_found';
    public const ADMIN_SECTIONS_DELETE_FORM_PREPARE = 'sfs_cms.admin.sections.delete_form_prepare';
    public const ADMIN_SECTIONS_DELETE_FORM_INIT = 'sfs_cms.admin.sections.delete_form_init';
    public const ADMIN_SECTIONS_DELETE_FORM_VALID = 'sfs_cms.admin.sections.delete_form_valid';
    public const ADMIN_SECTIONS_DELETE_APPLY = 'sfs_cms.admin.sections.delete_apply';
    public const ADMIN_SECTIONS_DELETE_SUCCESS = 'sfs_cms.admin.sections.delete_success';
    public const ADMIN_SECTIONS_DELETE_FAILURE = 'sfs_cms.admin.sections.delete_failure';
    public const ADMIN_SECTIONS_DELETE_FORM_INVALID = 'sfs_cms.admin.sections.delete_form_invalid';
    public const ADMIN_SECTIONS_DELETE_VIEW = 'sfs_cms.admin.sections.delete_view';
    public const ADMIN_SECTIONS_DELETE_EXCEPTION = 'sfs_cms.admin.sections.delete_exception';
    // SECTION UNPUBLISH EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTIONS_UNPUBLISH_INITIALIZE = 'sfs_cms.admin.sections.unpublish_initialize';
    public const ADMIN_SECTIONS_UNPUBLISH_LOAD_ENTITY = 'sfs_cms.admin.sections.unpublish_load_entity';
    public const ADMIN_SECTIONS_UNPUBLISH_NOT_FOUND = 'sfs_cms.admin.sections.unpublish_not_found';
    public const ADMIN_SECTIONS_UNPUBLISH_FOUND = 'sfs_cms.admin.sections.unpublish_found';
    public const ADMIN_SECTIONS_UNPUBLISH_APPLY = 'sfs_cms.admin.sections.unpublish_apply';
    public const ADMIN_SECTIONS_UNPUBLISH_SUCCESS = 'sfs_cms.admin.sections.unpublish_success';
    public const ADMIN_SECTIONS_UNPUBLISH_FAILURE = 'sfs_cms.admin.sections.unpublish_failure';
    public const ADMIN_SECTIONS_UNPUBLISH_EXCEPTION = 'sfs_cms.admin.sections.unpublish_exception';
    // SECTION PREVIEW EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTIONS_PREVIEW_INITIALIZE = 'sfs_cms.admin.sections.preview_initialize';
    public const ADMIN_SECTIONS_PREVIEW_LOAD_ENTITY = 'sfs_cms.admin.sections.preview_load_entity';
    public const ADMIN_SECTIONS_PREVIEW_NOT_FOUND = 'sfs_cms.admin.sections.preview_not_found';
    public const ADMIN_SECTIONS_PREVIEW_FOUND = 'sfs_cms.admin.sections.preview_found';
    public const ADMIN_SECTIONS_PREVIEW_VIEW = 'sfs_cms.admin.sections.preview_view';
    public const ADMIN_SECTIONS_PREVIEW_EXCEPTION = 'sfs_cms.admin.sections.preview_exception';

    // SECTION_VERSION CREATE EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTION_VERSIONS_CREATE_INITIALIZE = 'sfs_cms.admin.section_versions.create_initialize';
    public const ADMIN_SECTION_VERSIONS_CREATE_ENTITY = 'sfs_cms.admin.section_versions.create_create_entity';
    public const ADMIN_SECTION_VERSIONS_CREATE_FORM_PREPARE = 'sfs_cms.admin.section_versions.create_form_prepare';
    public const ADMIN_SECTION_VERSIONS_CREATE_FORM_INIT = 'sfs_cms.admin.section_versions.create_form_init';
    public const ADMIN_SECTION_VERSIONS_CREATE_FORM_VALID = 'sfs_cms.admin.section_versions.create_form_valid';
    public const ADMIN_SECTION_VERSIONS_CREATE_APPLY = 'sfs_cms.admin.section_versions.create_apply';
    public const ADMIN_SECTION_VERSIONS_CREATE_SUCCESS = 'sfs_cms.admin.section_versions.create_success';
    public const ADMIN_SECTION_VERSIONS_CREATE_FAILURE = 'sfs_cms.admin.section_versions.create_failure';
    public const ADMIN_SECTION_VERSIONS_CREATE_FORM_INVALID = 'sfs_cms.admin.section_versions.create_form_invalid';
    public const ADMIN_SECTION_VERSIONS_CREATE_VIEW = 'sfs_cms.admin.section_versions.create_view';
    public const ADMIN_SECTION_VERSIONS_CREATE_EXCEPTION = 'sfs_cms.admin.section_versions.create_exception';
    // SECTION_VERSION IMPORT EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTION_VERSIONS_IMPORT_INITIALIZE = 'sfs_cms.admin.section_versions.import_initialize';
    public const ADMIN_SECTION_VERSIONS_IMPORT_ENTITY = 'sfs_cms.admin.section_versions.import_import_entity';
    public const ADMIN_SECTION_VERSIONS_IMPORT_FORM_PREPARE = 'sfs_cms.admin.section_versions.import_form_prepare';
    public const ADMIN_SECTION_VERSIONS_IMPORT_FORM_INIT = 'sfs_cms.admin.section_versions.import_form_init';
    public const ADMIN_SECTION_VERSIONS_IMPORT_FORM_VALID = 'sfs_cms.admin.section_versions.import_form_valid';
    public const ADMIN_SECTION_VERSIONS_IMPORT_APPLY = 'sfs_cms.admin.section_versions.import_apply';
    public const ADMIN_SECTION_VERSIONS_IMPORT_SUCCESS = 'sfs_cms.admin.section_versions.import_success';
    public const ADMIN_SECTION_VERSIONS_IMPORT_FAILURE = 'sfs_cms.admin.section_versions.import_failure';
    public const ADMIN_SECTION_VERSIONS_IMPORT_FORM_INVALID = 'sfs_cms.admin.section_versions.import_form_invalid';
    public const ADMIN_SECTION_VERSIONS_IMPORT_VIEW = 'sfs_cms.admin.section_versions.import_view';
    public const ADMIN_SECTION_VERSIONS_IMPORT_EXCEPTION = 'sfs_cms.admin.section_versions.import_exception';
    // SECTION_VERSION LIST EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTION_VERSIONS_LIST_INITIALIZE = 'sfs_cms.admin.section_versions.list_initialize';
    public const ADMIN_SECTION_VERSIONS_LIST_FILTER_FORM_PREPARE = 'sfs_cms.admin.section_versions.list_filter_form_prepare';
    public const ADMIN_SECTION_VERSIONS_LIST_FILTER_FORM_INIT = 'sfs_cms.admin.section_versions.list_filter_form_init';
    public const ADMIN_SECTION_VERSIONS_LIST_FILTER = 'sfs_cms.admin.section_versions.list_filter';
    public const ADMIN_SECTION_VERSIONS_LIST_VIEW = 'sfs_cms.admin.section_versions.list_view';
    public const ADMIN_SECTION_VERSIONS_LIST_EXCEPTION = 'sfs_cms.admin.section_versions.list_exception';
    // SECTION_VERSION LOCK EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTION_VERSIONS_LOCK_INITIALIZE = 'sfs_cms.admin.section_versions.lock_initialize';
    public const ADMIN_SECTION_VERSIONS_LOCK_LOAD_ENTITY = 'sfs_cms.admin.section_versions.lock_load_entity';
    public const ADMIN_SECTION_VERSIONS_LOCK_NOT_FOUND = 'sfs_cms.admin.section_versions.lock_not_found';
    public const ADMIN_SECTION_VERSIONS_LOCK_FOUND = 'sfs_cms.admin.section_versions.lock_found';
    public const ADMIN_SECTION_VERSIONS_LOCK_APPLY = 'sfs_cms.admin.section_versions.lock_apply';
    public const ADMIN_SECTION_VERSIONS_LOCK_SUCCESS = 'sfs_cms.admin.section_versions.lock_success';
    public const ADMIN_SECTION_VERSIONS_LOCK_FAILURE = 'sfs_cms.admin.section_versions.lock_failure';
    public const ADMIN_SECTION_VERSIONS_LOCK_EXCEPTION = 'sfs_cms.admin.section_versions.lock_exception';
    // SECTION_VERSION RECOMPILE EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTION_VERSIONS_RECOMPILE_INITIALIZE = 'sfs_cms.admin.section_versions.recompile_initialize';
    public const ADMIN_SECTION_VERSIONS_RECOMPILE_LOAD_ENTITY = 'sfs_cms.admin.section_versions.recompile_load_entity';
    public const ADMIN_SECTION_VERSIONS_RECOMPILE_NOT_FOUND = 'sfs_cms.admin.section_versions.recompile_not_found';
    public const ADMIN_SECTION_VERSIONS_RECOMPILE_FOUND = 'sfs_cms.admin.section_versions.recompile_found';
    public const ADMIN_SECTION_VERSIONS_RECOMPILE_APPLY = 'sfs_cms.admin.section_versions.recompile_apply';
    public const ADMIN_SECTION_VERSIONS_RECOMPILE_SUCCESS = 'sfs_cms.admin.section_versions.recompile_success';
    public const ADMIN_SECTION_VERSIONS_RECOMPILE_FAILURE = 'sfs_cms.admin.section_versions.recompile_failure';
    public const ADMIN_SECTION_VERSIONS_RECOMPILE_EXCEPTION = 'sfs_cms.admin.section_versions.recompile_exception';
    // SECTION_VERSION CLEAR_COMPILED EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_INITIALIZE = 'sfs_cms.admin.section_versions.clear_compiled_initialize';
    public const ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_LOAD_ENTITY = 'sfs_cms.admin.section_versions.clear_compiled_load_entity';
    public const ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_NOT_FOUND = 'sfs_cms.admin.section_versions.clear_compiled_not_found';
    public const ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_FOUND = 'sfs_cms.admin.section_versions.clear_compiled_found';
    public const ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_APPLY = 'sfs_cms.admin.section_versions.clear_compiled_apply';
    public const ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_SUCCESS = 'sfs_cms.admin.section_versions.clear_compiled_success';
    public const ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_FAILURE = 'sfs_cms.admin.section_versions.clear_compiled_failure';
    public const ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_EXCEPTION = 'sfs_cms.admin.section_versions.clear_compiled_exception';
    // SECTION_VERSION PREVIEW EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTION_VERSIONS_PREVIEW_INITIALIZE = 'sfs_cms.admin.section_versions.preview_initialize';
    public const ADMIN_SECTION_VERSIONS_PREVIEW_LOAD_ENTITY = 'sfs_cms.admin.section_versions.preview_load_entity';
    public const ADMIN_SECTION_VERSIONS_PREVIEW_NOT_FOUND = 'sfs_cms.admin.section_versions.preview_not_found';
    public const ADMIN_SECTION_VERSIONS_PREVIEW_FOUND = 'sfs_cms.admin.section_versions.preview_found';
    public const ADMIN_SECTION_VERSIONS_PREVIEW_EXCEPTION = 'sfs_cms.admin.section_versions.preview_exception';
    public const ADMIN_SECTION_VERSIONS_PREVIEW_VIEW = 'sfs_cms.admin.section_versions.preview_view';
    // SECTION_VERSION PUBLISH EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTION_VERSIONS_PUBLISH_INITIALIZE = 'sfs_cms.admin.section_versions.publish_initialize';
    public const ADMIN_SECTION_VERSIONS_PUBLISH_LOAD_ENTITY = 'sfs_cms.admin.section_versions.publish_load_entity';
    public const ADMIN_SECTION_VERSIONS_PUBLISH_NOT_FOUND = 'sfs_cms.admin.section_versions.publish_not_found';
    public const ADMIN_SECTION_VERSIONS_PUBLISH_FOUND = 'sfs_cms.admin.section_versions.publish_found';
    public const ADMIN_SECTION_VERSIONS_PUBLISH_APPLY = 'sfs_cms.admin.section_versions.publish_apply';
    public const ADMIN_SECTION_VERSIONS_PUBLISH_SUCCESS = 'sfs_cms.admin.section_versions.publish_success';
    public const ADMIN_SECTION_VERSIONS_PUBLISH_FAILURE = 'sfs_cms.admin.section_versions.publish_failure';
    public const ADMIN_SECTION_VERSIONS_PUBLISH_EXCEPTION = 'sfs_cms.admin.section_versions.publish_exception';
    // SECTION_VERSION EXPORT EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTION_VERSIONS_EXPORT_INITIALIZE = 'sfs_cms.admin.section_versions.export_initialize';
    public const ADMIN_SECTION_VERSIONS_EXPORT_LOAD_ENTITY = 'sfs_cms.admin.section_versions.export_load_entity';
    public const ADMIN_SECTION_VERSIONS_EXPORT_NOT_FOUND = 'sfs_cms.admin.section_versions.export_not_found';
    public const ADMIN_SECTION_VERSIONS_EXPORT_FOUND = 'sfs_cms.admin.section_versions.export_found';
    public const ADMIN_SECTION_VERSIONS_EXPORT_APPLY = 'sfs_cms.admin.section_versions.export_apply';
    public const ADMIN_SECTION_VERSIONS_EXPORT_SUCCESS = 'sfs_cms.admin.section_versions.export_success';
    public const ADMIN_SECTION_VERSIONS_EXPORT_FAILURE = 'sfs_cms.admin.section_versions.export_failure';
    public const ADMIN_SECTION_VERSIONS_EXPORT_EXCEPTION = 'sfs_cms.admin.section_versions.export_exception';
    // SECTION VERSION CLEANUP EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTION_CLEANUP_VERSIONS_INITIALIZE = 'sfs_cms.admin.section_versions.cleanup_initialize';
    public const ADMIN_SECTION_CLEANUP_VERSIONS_LOAD_ENTITY = 'sfs_cms.admin.section_versions.cleanup_load_entity';
    public const ADMIN_SECTION_CLEANUP_VERSIONS_NOT_FOUND = 'sfs_cms.admin.section_versions.cleanup_not_found';
    public const ADMIN_SECTION_CLEANUP_VERSIONS_FOUND = 'sfs_cms.admin.section_versions.cleanup_found';
    public const ADMIN_SECTION_CLEANUP_VERSIONS_APPLY = 'sfs_cms.admin.section_versions.cleanup_apply';
    public const ADMIN_SECTION_CLEANUP_VERSIONS_SUCCESS = 'sfs_cms.admin.section_versions.cleanup_success';
    public const ADMIN_SECTION_CLEANUP_VERSIONS_FAILURE = 'sfs_cms.admin.section_versions.cleanup_failure';
    public const ADMIN_SECTION_CLEANUP_VERSIONS_EXCEPTION = 'sfs_cms.admin.section_versions.cleanup_exception';
    // SECTION VERSION INFO EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTION_VERSIONS_INFO_INITIALIZE = 'sfs_cms.admin.section_versions.info_initialize';
    public const ADMIN_SECTION_VERSIONS_INFO_LOAD_ENTITY = 'sfs_cms.admin.section_versions.info_load_entity';
    public const ADMIN_SECTION_VERSIONS_INFO_NOT_FOUND = 'sfs_cms.admin.section_versions.info_not_found';
    public const ADMIN_SECTION_VERSIONS_INFO_FOUND = 'sfs_cms.admin.section_versions.info_found';
    public const ADMIN_SECTION_VERSIONS_INFO_FORM_PREPARE = 'sfs_cms.admin.section_versions.info_form_prepare';
    public const ADMIN_SECTION_VERSIONS_INFO_FORM_INIT = 'sfs_cms.admin.section_versions.info_form_init';
    public const ADMIN_SECTION_VERSIONS_INFO_FORM_VALID = 'sfs_cms.admin.section_versions.info_form_valid';
    public const ADMIN_SECTION_VERSIONS_INFO_APPLY = 'sfs_cms.admin.section_versions.info_apply';
    public const ADMIN_SECTION_VERSIONS_INFO_SUCCESS = 'sfs_cms.admin.section_versions.info_success';
    public const ADMIN_SECTION_VERSIONS_INFO_FAILURE = 'sfs_cms.admin.section_versions.info_failure';
    public const ADMIN_SECTION_VERSIONS_INFO_FORM_INVALID = 'sfs_cms.admin.section_versions.info_form_invalid';
    public const ADMIN_SECTION_VERSIONS_INFO_VIEW = 'sfs_cms.admin.section_versions.info_view';
    public const ADMIN_SECTION_VERSIONS_INFO_EXCEPTION = 'sfs_cms.admin.section_versions.info_exception';
    // SECTION VERSION DELETE EVENTS, ALL OF THEM ARE INTERNAL
    public const ADMIN_SECTION_VERSIONS_DELETE_INITIALIZE = 'sfs_cms.admin.section_versions.delete_initialize';
    public const ADMIN_SECTION_VERSIONS_DELETE_LOAD_ENTITY = 'sfs_cms.admin.section_versions.delete_load_entity';
    public const ADMIN_SECTION_VERSIONS_DELETE_NOT_FOUND = 'sfs_cms.admin.section_versions.delete_not_found';
    public const ADMIN_SECTION_VERSIONS_DELETE_FOUND = 'sfs_cms.admin.section_versions.delete_found';
    public const ADMIN_SECTION_VERSIONS_DELETE_FORM_PREPARE = 'sfs_cms.admin.section_versions.delete_form_prepare';
    public const ADMIN_SECTION_VERSIONS_DELETE_FORM_INIT = 'sfs_cms.admin.section_versions.delete_form_init';
    public const ADMIN_SECTION_VERSIONS_DELETE_FORM_VALID = 'sfs_cms.admin.section_versions.delete_form_valid';
    public const ADMIN_SECTION_VERSIONS_DELETE_APPLY = 'sfs_cms.admin.section_versions.delete_apply';
    public const ADMIN_SECTION_VERSIONS_DELETE_SUCCESS = 'sfs_cms.admin.section_versions.delete_success';
    public const ADMIN_SECTION_VERSIONS_DELETE_FAILURE = 'sfs_cms.admin.section_versions.delete_failure';
    public const ADMIN_SECTION_VERSIONS_DELETE_FORM_INVALID = 'sfs_cms.admin.section_versions.delete_form_invalid';
    public const ADMIN_SECTION_VERSIONS_DELETE_VIEW = 'sfs_cms.admin.section_versions.delete_view';
    public const ADMIN_SECTION_VERSIONS_DELETE_EXCEPTION = 'sfs_cms.admin.section_versions.delete_exception';
}
