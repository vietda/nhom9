  // written by: All members
  // tested by: All members
  // debugged by: All members

package myrestaurant.main.dbtables;
import java.io.File;
import java.io.FileFilter;

import org.json.JSONArray;
import org.json.JSONException;

import android.content.Context;
import android.util.Log;
import myrestaurant.main.DBContentProvider;
import myrestaurant.main.MyRestaurantActivity;
import myrestaurant.main.HTTPHelper;

import com.thetransactioncompany.jsonrpc2.JSONRPC2Error;
import com.thetransactioncompany.jsonrpc2.JSONRPC2ParseException;
import com.thetransactioncompany.jsonrpc2.JSONRPC2Request;
import com.thetransactioncompany.jsonrpc2.JSONRPC2Response;

public class DBSyncManager {
	
	private static final String rpcUri = "emng/syncmanager.rpc.php";
	
	private Context context=null;
	DBContentProvider dbc = null;
	
	public DBSyncManager(Context c){
		context = c;
		dbc = new DBContentProvider(c);
	}
	
	public void close(){
		dbc.close();
	}
	
	public void syncMenus(){
		
		String method = "getMenus";
		String id = "syncmenus-1";
		JSONRPC2Request reqOut = new JSONRPC2Request(method, id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;

		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
		}

		// Check for success or error
		if (respIn.indicatesSuccess()) {
			
			try {
				dbc.syncMenus(new JSONArray((String)respIn.getResult()));
			} catch (JSONException e) {
				Log.e(MyRestaurantActivity.TAG,"JSON exception: "+e.getMessage());
			}
		}
		else {

			printError(respIn.getError());
			
		}
	}
	
	public void syncItems(){
		
		
		String method = "getItems";
		String id = "syncitems-1";
		JSONRPC2Request reqOut = new JSONRPC2Request(method, id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;
		
		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
		}

		// Check for success or error
		if (respIn.indicatesSuccess()) {
			
			try {
				dbc.syncItems(new JSONArray((String)respIn.getResult()));
			} catch (JSONException e) {
				Log.e(MyRestaurantActivity.TAG,"JSON exception: "+e.getMessage());
			}
		}
		else {

			printError(respIn.getError());
			
		}
		
	} 
	
	public void syncMenulists(){
		
		String method = "getMenulists";
		String id = "syncmenulists-1";
		JSONRPC2Request reqOut = new JSONRPC2Request(method, id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;
		
		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
		}

		// Check for success or error
		if (respIn.indicatesSuccess()) {
			
			try {
				dbc.syncMenulists(new JSONArray((String)respIn.getResult()));
			} catch (JSONException e) {
				Log.e(MyRestaurantActivity.TAG,"JSON exception: "+e.getMessage());
			}
		}
		else {

			printError(respIn.getError());
			
		}
	} 
	
	public void syncImages(){
		
		String method = "getImages";
		String id = "syncimages-1";
		JSONRPC2Request reqOut = new JSONRPC2Request(method, id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;
		
		
		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
		}

		// Check for success or error
		if (respIn.indicatesSuccess()) {
			
			try {
				//Prepare fo image sync
				deleteRecursive(context.getFilesDir());
				dbc.syncImagesTable(new JSONArray((String)respIn.getResult()));
				
			} catch (JSONException e) {
				Log.e(MyRestaurantActivity.TAG,"JSON exception: "+e.getMessage());
			}
		}
		else {

			printError(respIn.getError());
			
		}
		
		
	} 
	
	public void syncCategories(){
		
		String method = "getCategories";
		String id = "synccategories-1";
		JSONRPC2Request reqOut = new JSONRPC2Request(method, id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;
		
		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
		}

		// Check for success or error
		if (respIn.indicatesSuccess()) {
			
			try {
				dbc.syncCategories(new JSONArray((String)respIn.getResult()));
			} catch (JSONException e) {
				Log.e(MyRestaurantActivity.TAG,"JSON exception: "+e.getMessage());
			}
		}
		else {

			printError(respIn.getError());
			
		}
		
	} 
	
	public void syncConfig(){
		
		String method = "getConfig";
		String id = "syncconfig-1";
		JSONRPC2Request reqOut = new JSONRPC2Request(method, id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;
		
		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
		}

		// Check for success or error
		if (respIn.indicatesSuccess()) {
			
			try {
				dbc.syncConfig(new JSONArray((String)respIn.getResult()));
			} catch (JSONException e) {
				Log.e(MyRestaurantActivity.TAG,"JSON exception: "+e.getMessage());
			}
		}
		else {

			printError(respIn.getError());
			
		}
		
	} 
	
	public void syncFullMenu(){
		syncMenus();
		syncItems();
		syncCategories();
		syncMenulists();
		syncConfig();
		syncImages();
	}
	
	public void syncData(){
		syncMenus();
		syncItems();
		syncCategories();
		syncMenulists();
	}
	
	private void printError(JSONRPC2Error err){
		Log.e(MyRestaurantActivity.TAG,"The request failed :");
		Log.e(MyRestaurantActivity.TAG,"\terror.code    : " + err.getCode());
		Log.e(MyRestaurantActivity.TAG,"\terror.message : " + err.getMessage());
		Log.e(MyRestaurantActivity.TAG,"\terror.data    : " + err.getData());
	}

	private void deleteRecursive(File fileOrDirectory) {
	    for (File child : fileOrDirectory.listFiles(new MyFileFilter()))
	    	child.delete();
	}
	
	private class MyFileFilter implements FileFilter{

		@Override
		public boolean accept(File pathname) {
			return pathname.getName().endsWith(".img");
		}
		
	}
}
