  // written by: All members
  // tested by: All members
  // debugged by: All members

package myrestaurant.main;

import java.util.ArrayList;
import java.util.List;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.content.Context;
import android.util.Log;
import myrestaurant.main.dbtables.DBRowOrder;

import com.thetransactioncompany.jsonrpc2.JSONRPC2Error;
import com.thetransactioncompany.jsonrpc2.JSONRPC2ParseException;
import com.thetransactioncompany.jsonrpc2.JSONRPC2Request;
import com.thetransactioncompany.jsonrpc2.JSONRPC2Response;

public class Emrpc {
	
	private static final String rpcUri = "emng/emrpc.rpc.php";
	
	public static final int T_FIXED = 0;
	public static final int T_CARTE = 1;
	
	private Object data;
	private String error;
	private Context context = null;

	Emrpc(Context c){
		context =c;
	}
	/**
	 * 
	 * @return
	 */
	public ArrayList<String> getTablesList(){
		
		String method = "getTablesList";
		String id = "gettables-1";
		JSONRPC2Request reqOut = new JSONRPC2Request(method, id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;
		JSONArray jArray = null;
		ArrayList<String> tablesList = new ArrayList<String>();

		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
			return tablesList;
		}
		
		// Check for success or error
		if (respIn.indicatesSuccess()) {
			
			JSONObject jsonData = null;
			
			try {
				jArray = new JSONArray((String)respIn.getResult());
				if(jArray.length()>0){
					for(int i=0; i<jArray.length();i++){
						jsonData = jArray.getJSONObject(i);
						tablesList.add(jsonData.getString("tableName"));
					}
				}
					
			} catch (JSONException e) {
				Log.e(MyRestaurantActivity.TAG,"JSON exception: "+e.getMessage());
			}
		}
		else {
			printError(respIn.getError());
		}
		return tablesList;
	}
	/**
	 * 
	 * @return
	 */
	public ArrayList<String> getLoginNames(){
		
		String method = "getLoginNames";
		String id = "getlogins-1";
		JSONRPC2Request reqOut = new JSONRPC2Request(method, id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;
		JSONArray jArray = null;
		ArrayList<String> loginNames =  new ArrayList<String>();;

		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
			return loginNames;
		}
		
		// Check for success or error
		if (respIn.indicatesSuccess()) {
			
			JSONObject jsonData = null;
			
			try {
				jArray = new JSONArray((String)respIn.getResult());
				if(jArray.length()>0){
					for(int i=0; i<jArray.length();i++){
						jsonData = jArray.getJSONObject(i);
						loginNames.add(jsonData.getString("username"));
					}
				}
					
			} catch (JSONException e) {
				Log.e(MyRestaurantActivity.TAG,"JSON exception: "+e.getMessage());
			}
		}
		else {
			printError(respIn.getError());
		}
		return loginNames;
	}
	/**
	 * 
	 * @param user
	 * @param pass
	 * @param table
	 * @return
	 */
	public boolean newSession(String user,String pass, String table){
		
		String method = "checkLogin";
		String id = "checklogin-1";
		List<String> params = new ArrayList<String>();
		params.add(user);
		params.add(pass);
		params.add(table);
		JSONRPC2Request reqOut = new JSONRPC2Request(method, params,id);
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
			JSONArray jArray = null;
			JSONObject jsonData = null;
			
			try {
				jArray = new JSONArray((String)respIn.getResult());
				if(jArray.length()>0){
					for(int i=0; i<jArray.length();i++){
						jsonData = jArray.getJSONObject(i);
						data = jsonData.getString("sid");
					}
				}
					
			} catch (JSONException e) {
				Log.e(MyRestaurantActivity.TAG,"JSON exception: "+e.getMessage());
			}
			
			return true;
		}
		else {
			printError(respIn.getError());
			return false;
		}
		
	}
	/**
	 * 
	 * @param sid
	 * @param adults
	 * @param children
	 * @return
	 */
	public boolean setDinersNumber(String sid,String adults,String children){
		
		String method = "setDinersNumber";
		String id = "setdiners-1";
		List<String> params = new ArrayList<String>();
		params.add(sid);
		params.add(adults);
		params.add(children);
		JSONRPC2Request reqOut = new JSONRPC2Request(method, params,id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;

		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
			return false;
		}
		
		if (respIn.indicatesSuccess()){
			return true;
		}else{
			printError(respIn.getError());
			return false;
		}
		
	}
	/**
	 * 
	 * @param sid
	 * @param idMenulists
	 * @return
	 */
public boolean getItemNumber(String sid,int idMenulists){
		
		String method = "getItemNumber";
		String id = "getitemnumber-1";
		List<String> params = new ArrayList<String>();
		params.add(sid);
		params.add(Integer.toString(idMenulists));
		JSONRPC2Request reqOut = new JSONRPC2Request(method, params,id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;

		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
			return false;
		}
		
		if (respIn.indicatesSuccess()){
			JSONArray jArray = null;
			JSONObject jsonData = null;
			
			ArrayList<String> al = new ArrayList<String>();
			
			try {
				jArray = new JSONArray((String)respIn.getResult());
				if(jArray.length()>0){
					for(int i=0; i<jArray.length();i++){
						jsonData = jArray.getJSONObject(i);
						al.add(jsonData.getString("number"));
						al.add(jsonData.getString("note"));
					}
				data = al;
				}
			} catch (JSONException e) {
				Log.e(MyRestaurantActivity.TAG,"JSON exception: "+e.getMessage());
			}
			
			return true;
		
		}else{
			printError(respIn.getError());
			return false;
		}
		
	}
	/**
	 * 
	 * @param sid
	 * @param idMenulists
	 * @param number
	 * @param note
	 * @return
	 * @throws Exception
	 */
	public boolean addToOrder(String sid,int idMenulists,int number,String note) throws Exception {
		
		String method = "addToOrder";
		String id = "addtoorder-1";
		List<String> params = new ArrayList<String>();
		params.add(sid);
		params.add(Integer.toString(idMenulists));
		params.add(Integer.toString(number));
		params.add(note);
		JSONRPC2Request reqOut = new JSONRPC2Request(method, params,id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;

		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
			return false;
		}
		
		if(!respIn.indicatesSuccess()){
			throw new Exception(respIn.getError().getMessage());
		}
		
		return true;
	}
	/**
	 * 
	 * @param type
	 * @param sid
	 * @return
	 */
	public ArrayList<DBRowOrder> getOrderItems(int type,String sid){
		String method = "getOrderItems";
		String id = "getorder-1";
		List<String> params = new ArrayList<String>();
		params.add(Integer.toString(type));
		params.add(sid);
		
		JSONRPC2Request reqOut = new JSONRPC2Request(method, params,id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;
		ArrayList<DBRowOrder> dbrl = null; 
		
		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
			return null;
		}
		
		if(respIn.indicatesSuccess()){
			JSONArray jArray = null;
			JSONObject jsonData = null;
			dbrl = new ArrayList<DBRowOrder>();
			
			try {
				jArray = new JSONArray((String)respIn.getResult());
				if(jArray.length()>0){
					for(int i=0; i<jArray.length();i++){
						DBRowOrder dbro = new DBRowOrder(); 
						jsonData = jArray.getJSONObject(i);
						dbro.setType(type);
						dbro.setLabel(jsonData.getString("label"));	
						dbro.setPrice(jsonData.getString("price"));
						dbro.setNumber(jsonData.getString("number"));
						dbrl.add(dbro);
					}
				}
				return dbrl;
			} catch (JSONException e) {
				Log.e(MyRestaurantActivity.TAG,"JSON exception: "+e.getMessage());
				return null;
			}
			
		}else{
			printError(respIn.getError());
			return null;
		}
		
	}
/**
 * 
 * @param sid
 * @return
 */
	public String getBill(String sid){
		String method = "getBill";
		String id = "getbill-1";
		List<String> params = new ArrayList<String>();
		params.add(sid);
		
		JSONRPC2Request reqOut = new JSONRPC2Request(method, params,id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;
		
		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
			return null;
		}
		
		if(respIn.indicatesSuccess()){
				return (String)respIn.getResult();
		}else{
			printError(respIn.getError());
			return null;
		}
		
	}
	/**
	 * 
	 * @param sid
	 * @return
	 * @throws Exception
	 */
	public boolean sendOrder(String sid) throws Exception {
		
		String method = "sendOrder";
		String id = "sendorder-1";
		List<String> params = new ArrayList<String>();
		params.add(sid);
		JSONRPC2Request reqOut = new JSONRPC2Request(method, params,id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;

		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
			return false;
		}
		
		if(!respIn.indicatesSuccess()){
			throw new Exception(Integer.toString(respIn.getError().getCode()));
		}
		
		return true;
	}
	/**
	 * 
	 * @param sid
	 * @return
	 */
	public boolean callWaiter(String sid){
		
		String method = "callWaiter";
		String id = "callwaiter-1";
		List<String> params = new ArrayList<String>();
		params.add(sid);
		JSONRPC2Request reqOut = new JSONRPC2Request(method, params,id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;

		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
			return false;
		}
		
		if(respIn.indicatesSuccess()){
			return true;
		}
		else
			return false;
		
	}	
	/**
	 * 
	 * @param sid
	 * @return
	 * @throws Exception
	 */
	
	public boolean payBill(String sid) throws Exception{
		String method = "payBill";
		String id = "paybill-1";
		List<String> params = new ArrayList<String>();
		params.add(sid);
		
		JSONRPC2Request reqOut = new JSONRPC2Request(method, params,id);
		String jsonString = reqOut.toString();
		Log.i(MyRestaurantActivity.TAG,jsonString);
		HTTPHelper httph = new HTTPHelper(rpcUri,context);
		httph.setBody(jsonString);
		
		JSONRPC2Response respIn = null;
		
		try {
			respIn = JSONRPC2Response.parse(httph.executePost());
		} catch (JSONRPC2ParseException e) {
			Log.e(MyRestaurantActivity.TAG,"Error parsing JSONRPC2 response string: "+e.getMessage());
			return false;
		}
		
		if(!respIn.indicatesSuccess()){
			throw new Exception(Integer.toString(respIn.getError().getCode()));
		}
		
		return true;
		
	}
	
	public Object getData(){
		return data;
	}
	
	public String getError(){
		return error;
	}
	
	
	private void printError(JSONRPC2Error err){
		error = "The request failed :"+"\n\terror.code    : " + err.getCode()+
		"\n\terror.message : " + err.getMessage()+
		"\n\terror.data    : " + err.getData();
		
		Log.e(MyRestaurantActivity.TAG,error);
		
	}

}
