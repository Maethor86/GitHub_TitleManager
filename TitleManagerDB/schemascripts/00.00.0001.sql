


SET NOCOUNT ON;

BEGIN TRY
	BEGIN TRANSACTION	

	IF EXISTS(SELECT DB_NAME() WHERE DB_NAME() NOT IN ('$(DBNAME)')) USE $(DBNAME);

	PRINT 'Dropping table [dbo].[Web_Errors]...'

	DROP Table [dbo].[Web_Errors];


	SET ANSI_NULLS ON
	SET QUOTED_IDENTIFIER ON

	PRINT 'Creating table [dbo].[Errorstrings]...'

	CREATE TABLE [dbo].[Errorstrings](
		[ErrorstringID] [int] IDENTITY(1,1) NOT NULL,
		[Errorstring] [nvarchar](4000) NOT NULL,
		[DateTimeFirstLogged] [datetime2](7) NOT NULL,
	 CONSTRAINT [PK_Errorstrings] PRIMARY KEY CLUSTERED 
	(
		[ErrorstringID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	PRINT 'Creating table [dbo].[Errors]...'

	CREATE TABLE [dbo].[Errors](
		[ErrorID] [int] IDENTITY(1,1) NOT NULL,
		[DateTimeLogged] [datetime2](7) NOT NULL,
		[GeneratedByUser] [int] NULL,
		[ErrorstringID] [int] NOT NULL,
		[ExceptionCode] [int] NOT NULL,
		[GeneratedByIPAddress] nvarchar(45) NULL,
	 CONSTRAINT [PK_Errors] PRIMARY KEY CLUSTERED 
	(
		[ErrorID] ASC
	)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
	) ON [PRIMARY]

	PRINT 'Updating column [ScriptDescription] in table [dbo].[SchemaChanges] ...'

	ALTER TABLE [dbo].[SchemaChanges] ALTER COLUMN [ScriptDescription] nvarchar(500) NOT NULL;


	PRINT 'Adding indexes(?) and constraints...'

	ALTER TABLE [dbo].[Errors]	ADD  CONSTRAINT [CK_NotBothNULL_GeneratedByUser_GeneratedByIPAddress] CHECK ([GeneratedByUser] IS NOT NULL OR [GeneratedByIPAddress] IS NOT NULL);
	

	ALTER TABLE [dbo].[Errors]  WITH CHECK ADD  CONSTRAINT [FK_Errors_Web_Users] FOREIGN KEY([GeneratedByUser])
	REFERENCES [dbo].[Web_Users] ([UserID])

	ALTER TABLE [dbo].[Errors]  WITH CHECK ADD  CONSTRAINT [FK_Errors_Errorstrings] FOREIGN KEY([ErrorstringID])
	REFERENCES [dbo].[Errorstrings] ([ErrorstringID])

	
	
	PRINT 'Updating table [dbo].[SchemaChanges] with SchemaChangeNumber...'

	DECLARE @MajorReleaseNumber nvarchar(2) = N'00';
	DECLARE @MinorReleaseNumber nvarchar(2) = N'00';
	DECLARE @PointReleaseNumber nvarchar(4) = N'0001';
	DECLARE @ScriptDescription nvarchar(500) = 'CREATEs TABLEs Errors, Errorstrings. DROPs TABLE Web_Errors. ALTERs datatype of column ScriptDescription in table SchemaChanges.';
	INSERT [dbo].[SchemaChanges] ([MajorReleaseNumber], [MinorReleaseNumber], [PointReleaseNumber], [ScriptDescription], [DateTimeApplied]) VALUES (@MajorReleaseNumber, @MinorReleaseNumber, @PointReleaseNumber, @ScriptDescription, SYSDATETIME());

	COMMIT TRANSACTION
END TRY
BEGIN CATCH
	IF (@@TRANCOUNT > 0) ROLLBACK TRANSACTION
	DECLARE @ERROR_MSG nvarchar(2048) = error_message()
	DECLARE @ERROR_SEVERITY nvarchar(2048) = error_severity()
	DECLARE @ERROR_STATE nvarchar(2048) = error_state()
	RAISERROR (@ERROR_MSG, @ERROR_SEVERITY, @ERROR_STATE)
END CATCH
GO

SET NOCOUNT OFF;
GO