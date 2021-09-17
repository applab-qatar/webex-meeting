<p align="center"><a href="https://applab.qa" target="_blank"><img src="https://applab.qa/wp-content/uploads/2020/11/page-logo.svg" width="400"></a></p>

<p align="center"></p>

## Webex meeting
Webex meeting is a package for Laravel 8 which allows you manage webex meeting and invitee over your laravel.
The package allows you to manage your events and its attendees with Webex meeting and its invitees.

Below are a full list of features:
- Meeting
    - Creates a new meeting.
    - List Meetings
    - Retrieves details for a meeting with a specified meeting ID.
    - Update a Meeting
    - Deletes a meeting with a specified meeting ID
- Invitees
    - Lists meeting invitees for a meeting with a specified meetingId.
    - Invites a person to attend a meeting.
    - Retrieves details for a meeting invitee
    - Retrieves details for a meeting invitee

## About Applab

AppLab is a leading company specialized in online platforms development. Online Platforms include Back-end, Databases, Web Applications and Mobile

## About Webex Platform

[Webex](https://www.webex.com) Meetings is a powerful conferencing solution that lets you connect with anyone, anywhere, in real time. By combining video, audio and content sharing, Webex Meetings creates an effective conferencing environment, leading to more satisfying meetings and increased productivity.

- [Getting Started](https://developer.webex.com/docs/api/getting-started)
- [Integration](https://developer.webex.com/docs/integrations)
- [Webex REST API Basics](https://developer.webex.com/docs/api/basics)

## Meetings
Meetings are virtual conferences where users can collaborate in real time using audio, video, content sharing, chat, online whiteboards, and to collaborate.

This API focuses primarily on the scheduling and management of meetings. You can use the Meetings API to list, create, get, update, and delete meetings.

Several types of meeting objects are supported by this API, such as meeting series, scheduled meeting, and ended or in-progress meeting instances. See the [Webex Meetings](https://developer.webex.com/docs/api/guides/webex-meetings-rest-api) guide for more information about the types of meetings.

#### What are Integrations?
- Register your integration with Webex
- Request permission using an OAuth Grant Flow
- Exchange the resulting authorization code for an access token
- Use the access token to make your API calls

## Installing Webex Meeting

The recommended way to install Webex Meeting is through
[Composer](https://getcomposer.org/).

```bash
composer require applab/webex-meeting
```
Publish configuration and migrations
```bash
php artisan vendor:publish --provider="Applab\WebexMeeting\WebexMeetingServiceProvider"
```

The service provider is loaded automatically using [package discovery](https://laravel.com/docs/5.7/packages#package-discovery).
## Usage

### Configuration
The package ships with a configuration file called applab-webex.php which is published to the config directory during installation. Below is an outline of the settings.
```bash
client-id 
```
Issued when creating your webex app
```bash
client-secret
```
Remember this guy? You kept it safe somewhere when creating your integration

## Security Vulnerabilities

If you discover a security vulnerability within this package, please send an e-mail to Manu Applab via [manu@applab.qa](mailto:manu@applab.qa). All security vulnerabilities will be promptly addressed.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
