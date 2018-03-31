<?php


class CannotFindClassException extends Exception {
  function __construct($message, $code = ExceptionCode_CannotFindClass) {
        parent::__construct($message, $code);
    }
}

class WrongFormatException extends Exception {
  function __construct($message, $code = ExceptionCode_WrongFormat) {
        parent::__construct($message, $code);
    }
}

class DatabaseConnectionFailedException extends CriticalDatabaseException {
  function __construct($message, $code = ExceptionCode_DatabaseConnectionFailed) {
        parent::__construct($message, $code);
    }
}

class DatabaseQueryFailedException extends NonCriticalDatabaseException {
  function __construct($message, $code = ExceptionCode_DatabaseQueryFailed) {
        parent::__construct($message, $code);
    }
}


class DatabaseException extends Exception {
  function __construct($message, $code = ExceptionCode_Database) {
        parent::__construct($message, $code);
    }
}

class CriticalDatabaseException extends DatabaseException {
  // function __construct($message, $code = ExceptionCode_DatabaseCritical) {
  //       parent::__construct($message, $code);
  //   }
}

class NonCriticalDatabaseException extends DatabaseException {
  // function __construct($message, $code = ExceptionCode_DatabaseNonCritical) {
  //       parent::__construct($message, $code);
  //   }
}
?>
