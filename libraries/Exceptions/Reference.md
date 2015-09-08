Error Reference
===============

All of the following errors are subclassed from the `APIException` class.

| Error Code | Error Name                           | Error Message                                        |
| ---------- | ------------------------------------ | ---------------------------------------------------- |
| 1          | `ClassNotFoundException`             | Required class `$className` not found                |
| 2          | `ControllerNotFoundException`        | Unable to find a suitable controller given the path  |
| 3          | `IncorrectModelException`            | Incorrect model provided to view                     |
| 4          | `UnspecifiedViewException`           | Cannot respond with adequate view to request         |
| 5          | `MethodNotAllowedException`          | HTTP method not allowed                              |
| 6          | `UnhandledInternalResponseException` | Unhandled internal response                          |
