# CMS Sections Plugin

![Latest Stable](https://img.shields.io/packagist/v/softspring/cms-sections-plugin?label=stable&style=flat-square)
![Latest Unstable](https://img.shields.io/packagist/v/softspring/cms-sections-plugin?label=unstable&style=flat-square&include_prereleases)
![License](https://img.shields.io/packagist/l/softspring/cms-sections-plugin?style=flat-square)
![PHP Version](https://img.shields.io/packagist/dependency-v/softspring/cms-sections-plugin/php?style=flat-square)
![Downloads](https://img.shields.io/packagist/dt/softspring/cms-sections-plugin?style=flat-square)
[![CI](https://img.shields.io/github/actions/workflow/status/softspring/cms-sections-plugin/ci.yml?branch=6.1&style=flat-square&label=CI)](https://github.com/softspring/cms-sections-plugin/actions/workflows/ci.yml)
[![Coverage](https://img.shields.io/codecov/c/github/softspring/cms-sections-plugin?branch=6.1&style=flat-square)](https://app.codecov.io/gh/softspring/cms-sections-plugin/tree/6.1)

## Documentation

[Armonic documentation](https://armonic.softspring.es/6.1-dev/bundles/cms-bundle/sections)

## Database migrations

The plugin registers its own Doctrine migrations for section tables and relationships.
`Version20261109100000` normalizes `cms_content_version_sections.content_version_id` after the CMS identifier normalization.
It preserves the foreign key and supports both MySQL and PostgreSQL. Roll back this plugin migration before rolling back the earlier CMS migration.
