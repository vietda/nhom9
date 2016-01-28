  // written by: All members
  // tested by: All members
  // debugged by: All members

package myrestaurant.main;

import android.content.Context;
import android.content.SharedPreferences;

public class EmPrefs {

	private static final String SHARED_PREFS_NAME = "settings";
	
	private String sid = null;
	SharedPreferences settings = null;
	SharedPreferences.Editor editor = null;
	Context context = null;
	
	EmPrefs(Context c){
		
		context = c;
		settings = c.getSharedPreferences(SHARED_PREFS_NAME, Context.MODE_PRIVATE);
		editor = settings.edit();
	}
	
	public void commit(){
		editor.commit();
	}

	public String getSid() {
		return settings.getString("sid", "");
	}

	public void setSid(String sid) {
		editor.putString("sid", sid);
	}
	
	public String getValue(String key){
		return settings.getString(key, "");
	}
	
	public void setKeyValue(String key, String value){
		editor.putString(key, value);
	}
}
