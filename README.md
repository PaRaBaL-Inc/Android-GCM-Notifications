<h3><b>Sending Android notifications with GCM</b></h3>

<h4> SETUP </h4>

1. Copy the GcmBroadcastReceiver.java and GcmIntentService.java files into your Android project
2. Modifiy your AndroidManifest.xml to include
	```
	<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED" />
    	<uses-permission android:name="android.permission.INTERNET" />
    	<uses-permission android:name="android.permission.WAKE_LOCK" />
    	<uses-permission android:name="android.permission.GET_ACCOUNTS" />
    	<uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
    	<uses-permission android:name="com.google.android.c2dm.permission.RECEIVE" />

    <permission android:name="com.parabal.parabaltaskmanager.permission.C2D_MESSAGE"
        android:protectionLevel="signature" />
    <uses-permission android:name="com.parabal.parabaltaskmanager.permission.C2D_MESSAGE" />
        <receiver
            android:name=".GcmBroadcastReceiver"
            android:permission="com.google.android.c2dm.permission.SEND" >
            <intent-filter>
                <action android:name="com.google.android.c2dm.intent.RECEIVE" />
                <action android:name="android.intent.action.BOOT_COMPLETED" />
                <category android:name="com.parabal.parabaltaskmanager" />
            </intent-filter>
        </receiver>
        <receiver android:name=".ConnectivityReceiver" >
            <intent-filter>
                <action android:name="com.parabal.parabaltaskmanager.START_CONNECTIVITY"/>
            </intent-filter>
        </receiver>

        <service android:name=".GcmIntentService" />
        <service android:name=".UploadService" />
	```
3. Copy push.php to your server
4. Register your Google API key
	1. Go to https://console.developers.google.com
	2. Creagte a project
	3. Under 'APIs&auth > APIs' search for 'Google Cloud Messaging for Android' and enable
	4. Under 'APIs&auth > Credentials' generate a new 'key for server applications' and copy it
5. Open push.php and replace they API key with the one you just generated


<h4>USEAGE</h4>
1. Your app needs to register the device if it has not already. This can be done with
	```
	private void registerInBackground()
        {
            new AsyncTask<Void, Void, String>()
            {
                @Override
                protected String doInBackground(Void... params)
                {
                    String msg = "";
                    try
                    {
                        if (gcm == null)
                        {
                            gcm = GoogleCloudMessaging.getInstance(context);
                        }
                        regid = gcm.register(SENDER_ID);
                        msg = "Device registered, registration ID=" + regid;

                    }

                    catch (IOException ex)
                    {
                        // If there is an error, don't just keep trying to register.
                        // Require the user to click a button again, or perform
                        // exponential back-off.
                        msg = "Error :" + ex.getMessage();
                    }
                    return msg;
                }

                @Override
                protected void onPostExecute(String msg)
                {
                    Log.d("MainActivity", msg);
                    sendGCM();
                }
            }.execute(null, null, null);
        }
	```
	
	The registration id will try to save under regid. You can change this.
	Save this on the server. If you are unsure how to do this, use the library found under https://github.com/PaRaBaL-Inc/Android-Token-Authentication

2.) To send a notification, simply call 
	```
	<?php

	include 'push.php';
	$arr_users[] = "user";
	sendPushToUsers($arr_users, "Test notification");

	?>
	```




	
 

		




