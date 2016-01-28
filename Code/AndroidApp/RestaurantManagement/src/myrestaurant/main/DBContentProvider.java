  // written by: All members
  // tested by: All members
  // debugged by: All members

package myrestaurant.main;

import java.io.BufferedInputStream;
import java.io.ByteArrayOutputStream;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.util.ArrayList;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.util.Log;
import myrestaurant.main.dbtables.DBRowItem;
import myrestaurant.main.dbtables.DBRowMenus;
import myrestaurant.main.dbtables.DBTables;
import myrestaurant.main.dbtables.DBTables.Categories;
import myrestaurant.main.dbtables.DBTables.Config;
import myrestaurant.main.dbtables.DBTables.Images;
import myrestaurant.main.dbtables.DBTables.Items;
import myrestaurant.main.dbtables.DBTables.MenuLists;
import myrestaurant.main.dbtables.DBTables.Menus;

public class DBContentProvider extends SQLiteOpenHelper{
	
	private static final String TAG = "DBContentProvider";
	private static final String DATABASE_NAME = "emng.db";
	private static final int DATABASE_VERSION = 1;
	
	private Context _context;
		
	public DBContentProvider(Context context){
		super(context, DATABASE_NAME, null, DATABASE_VERSION);
		_context = context;
	}
	
	  @Override
      public void onCreate(SQLiteDatabase db) {
      	Log.w(MyRestaurantActivity.TAG+"."+TAG,"Creating the database");
          db.execSQL(Menus.CREATE_STATEMENT);
          db.execSQL(Categories.CREATE_STATEMENT);
          db.execSQL(Items.CREATE_STATEMENT);
          db.execSQL(MenuLists.CREATE_STATEMENT);
          db.execSQL(Images.CREATE_STATEMENT);
          db.execSQL(Config.CREATE_STATEMENT);
          
          /*BufferedReader br = new BufferedReader(new InputStreamReader(_context.getResources().openRawResource(R.raw.emngsql)));
          
          String line = null;
          
          try {
			while((line = br.readLine())!=null){
				db.execSQL(line);
				Log.i("EasymenuNG","Line "+line);
			  }
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}*/
	  }

      @Override
      public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
          Log.w(TAG, "Upgrading database from version " + oldVersion + " to " + newVersion
                  + ", which will destroy all old data");
          db.execSQL("DROP TABLE IF EXISTS " + Menus.TAG);
          db.execSQL("DROP TABLE IF EXISTS " + Categories.TAG);
          db.execSQL("DROP TABLE IF EXISTS " + Items.TAG);
          db.execSQL("DROP TABLE IF EXISTS " + MenuLists.TAG);
          db.execSQL("DROP TABLE IF EXISTS " + Images.TAG);
          onCreate(db);
      }
	
      public  ArrayList<DBRowMenus> getMenuTitles(boolean isDrinks ){
    	 String rawQuery;
    	 if(!isDrinks)
    		 rawQuery = "select _id,label,menuType from menus where visible='y' and foodbev='f' order by position asc;";
    	 else
    		 rawQuery = "select _id,label,menuType from menus where visible='y' and foodbev='b' order by position asc;";
    	 
    	 Cursor cur = getReadableDatabase()
 				.rawQuery(rawQuery,null);
    	 ArrayList<DBRowMenus> dbrm = new ArrayList<DBRowMenus>();
    	 cur.moveToFirst();
    	 while(!cur.isAfterLast()){
    		 
    		 DBRowMenus dbr = new DBRowMenus();
    		 
    		 dbr.setId(cur.getInt(0));
    		 dbr.setLabel(cur.getString(1));
    		 dbr.setMenuType(cur.getString(2));
    		 
    		 dbrm.add(dbr);
    		 cur.moveToNext();
    	 }
    	 cur.close();
    	 return dbrm;
      }
      
      public ArrayList<DBRowItem> getItemsList(int idMenu){
    	  
    	  String rawQuery;
    	  
    	  rawQuery = "select i._id,i.label,i.description,i.idImage,ml.price,ml._id as idMenulists,m.menuType," +
    	  		"c.label,ml.categoryPosition,ml.itemPosition \r\n" + 
    	  		"from \r\n" + 
    	  		"items i JOIN menulists ml on i._id = ml.fk_idItems \r\n" + 
    	  		"JOIN menus m on ml.fk_idMenus =m._id JOIN categories c ON c._id=ml.fk_idCategories\r\n" + 
    	  		"WHERE ml.fk_idMenus="+idMenu+" \r\n" + 
    	  		"order by ml.categoryPosition,ml.itemPosition asc; ";
    	  
    	  Cursor cur = getReadableDatabase()
			.rawQuery(rawQuery,null);
    	  ArrayList<DBRowItem> dbri = new ArrayList<DBRowItem>();
    	  cur.moveToFirst();
    	  
    	  while(!cur.isAfterLast()){
    		 
    		  DBRowItem dbr = new DBRowItem();
    		  dbr.setId(cur.getInt(0));
    		  dbr.setLabel(cur.getString(1));
    		  dbr.setDescription(cur.getString(2));
    		  dbr.setIdImage(cur.getInt(3));
    		  dbr.setPrice(cur.getString(4));
    		  dbr.setIdMenulists(cur.getInt(5));
    		  dbr.setMenuType(cur.getString(6));
    		  dbr.setCategory(cur.getString(7));
    		  dbri.add(dbr);
     		  cur.moveToNext();
    	  }
    	  cur.close();
     	  return dbri;
      }
      /**
       * 
       * @param jArray
       */
      public void syncMenus(JSONArray jArray){
    	  
    	JSONObject jsonData = null;
  		ContentValues cv = new ContentValues();
    	
  		getReadableDatabase().delete(DBTables.Menus.TAG, null, null);
  		SQLiteDatabase db = getWritableDatabase();
  		
  		try{
			for(int i=0;i<jArray.length();i++){

				jsonData = jArray.getJSONObject(i);
				cv.put(DBTables.Menus.ID_MENUS, jsonData.getInt("idMenus"));
				cv.put(DBTables.Menus.LABEL, jsonData.getString("label"));
				cv.put(DBTables.Menus.DESCRIPTION, jsonData.getString("description"));
				cv.put(DBTables.Menus.ID_IMAGE, jsonData.getInt("idImage"));
				cv.put(DBTables.Menus.EXTRAS, jsonData.getString("extras"));
				cv.put(DBTables.Menus.PRICE, jsonData.getString("price"));
				cv.put(DBTables.Menus.VISIBLE, jsonData.getString("visible"));
				cv.put(DBTables.Menus.MENU_TYPE, jsonData.getString("menuType"));
				cv.put(DBTables.Menus.FOODBEV_FLAG, jsonData.getString("foodbev"));
				cv.put(DBTables.Menus.MENU_POSITION, jsonData.getString("position"));
				
				db.insert(DBTables.Menus.TAG, "_id", cv);
			}
  		}
		catch(JSONException e){
			Log.e(MyRestaurantActivity.TAG,"JSON Exception :"+e.getMessage());
		}
  		
      }
      
      public void syncItems(JSONArray jArray){
    	  
    	  JSONObject jsonData = null;
    	  ContentValues cv = new ContentValues();

    	  getReadableDatabase().delete(DBTables.Items.TAG, null, null);
    	  SQLiteDatabase db = getWritableDatabase();

    	  try{
    		  for(int i=0;i<jArray.length();i++){

    			  jsonData = jArray.getJSONObject(i);
    			  cv.put(DBTables.Items.ID_ITEMS, jsonData.getInt("idItems"));
    			  cv.put(DBTables.Items.LABEL, jsonData.getString("label"));
    			  cv.put(DBTables.Items.DESCRIPTION, jsonData.getString("description"));
    			  cv.put(DBTables.Items.ID_IMAGE, jsonData.getInt("idImage"));
    			  cv.put(DBTables.Items.EXTRAS, jsonData.getString("extras"));
    			  cv.put(DBTables.Items.PRICE, jsonData.getString("price"));
    			  db.insert(DBTables.Items.TAG, "_id", cv);
    		  }
    	  }
    	  catch(JSONException e){
    		  Log.e(MyRestaurantActivity.TAG,"JSON Exception :"+e.getMessage());
    	  }

      }
      
      public void syncCategories(JSONArray jArray){
    	  
    	  JSONObject jsonData = null;
    	  ContentValues cv = new ContentValues();

    	  getReadableDatabase().delete(DBTables.Categories.TAG, null, null);
    	  SQLiteDatabase db = getWritableDatabase();

    	  try{
    		  for(int i=0;i<jArray.length();i++){

    			  jsonData = jArray.getJSONObject(i);
    			  cv.put(DBTables.Categories.ID_CATEGORIES, jsonData.getInt("idCategories"));
    			  cv.put(DBTables.Categories.LABEL, jsonData.getString("label"));
    			  cv.put(DBTables.Categories.DESCRIPTION, jsonData.getString("description"));
    			  cv.put(DBTables.Categories.ID_IMAGE, jsonData.getInt("idImage"));
    			  cv.put(DBTables.Categories.EXTRAS, jsonData.getString("extras"));
    			  db.insert(DBTables.Categories.TAG, "_id", cv);
    		  }
    	  }
    	  catch(JSONException e){
    		  Log.e(MyRestaurantActivity.TAG,"JSON Exception :"+e.getMessage());
    	  }

      }
      
      public void syncConfig(JSONArray jArray){
    	  
    	  EmPrefs emp = new EmPrefs(_context);
    	  JSONObject jsonData = null;
    	  ContentValues cv = new ContentValues();

    	  getReadableDatabase().delete(DBTables.Config.TAG, null, null);
    	  SQLiteDatabase db = getWritableDatabase();
    	  

    	  try{
    		  for(int i=0;i<jArray.length();i++){

    			  jsonData = jArray.getJSONObject(i);
    			  cv.put(DBTables.Config.DEVICE, jsonData.getString("device"));
    			  cv.put(DBTables.Config.KEY, jsonData.getString("key"));
    			  cv.put(DBTables.Config.VALUE, jsonData.getString("value"));
    			  db.insert(DBTables.Config.TAG, "_id", cv);
    			  emp.setKeyValue(jsonData.getString("key"), jsonData.getString("value"));
    		  }
    		  emp.commit();
    	  }
    	  catch(JSONException e){
    		  Log.e(MyRestaurantActivity.TAG,"JSON Exception :"+e.getMessage());
    	  }

      }
      

      public void syncMenulists(JSONArray jArray){
    	  
    	  JSONObject jsonData = null;
    	  ContentValues cv = new ContentValues();

    	  getReadableDatabase().delete(DBTables.MenuLists.TAG, null, null);
    	  SQLiteDatabase db = getWritableDatabase();

    	  try{
    		  for(int i=0;i<jArray.length();i++){

    			  jsonData = jArray.getJSONObject(i);
    			  cv.put(DBTables.MenuLists.ID_MENULISTS, jsonData.getInt("idMenulists"));
    			  cv.put(DBTables.MenuLists.FK_IDMENUS, jsonData.getInt("fk_idMenus"));
    			  cv.put(DBTables.MenuLists.FK_IDCATEGORIES, jsonData.getInt("fk_idCategories"));
    			  cv.put(DBTables.MenuLists.FK_IDITEMS, jsonData.getInt("fk_idItems"));
    			  cv.put(DBTables.MenuLists.PRICE, jsonData.getString("price"));
    			  cv.put(DBTables.MenuLists.CATEGORY_POS, jsonData.getInt("categoryPosition"));
    			  cv.put(DBTables.MenuLists.ITEM_POS, jsonData.getInt("itemPosition"));
    			  db.insert(DBTables.MenuLists.TAG, "_id", cv);
    		  }
    	  }
    	  catch(JSONException e){
    		  Log.e(MyRestaurantActivity.TAG,"JSON Exception :"+e.getMessage());
    	  }

      }
      
      public void syncImagesTable(JSONArray jArray){
    	  
    	  JSONObject jsonData = null;
    	  ContentValues cv = new ContentValues();

    	  getReadableDatabase().delete(DBTables.Images.TAG, null, null);
    	  SQLiteDatabase db = getWritableDatabase();

    	  try{
    		  for(int i=0;i<jArray.length();i++){

    			  jsonData = jArray.getJSONObject(i);
    			  cv.put(DBTables.Images.ID_IMAGES, jsonData.getInt("idImages"));
    			  db.insert(DBTables.Images.TAG, "_id", cv);
    			  
    			  syncImages(jsonData.getString("fileName"),jsonData.getInt("idImages"));
    		  }
    		  
    		  syncImages("logo.png",-1);
    	  }
    	  catch(JSONException e){
    		  Log.e(MyRestaurantActivity.TAG,"JSON Exception :"+e.getMessage());
    	  }
      }

     /* public ArrayList<DBRowOrder> getOrderList(JSONArray jArray){
    	JSONObject jsonData = null;
    	ArrayList<DBRowOrder> dbr = new ArrayList<DBRowOrder>();
    	Cursor cur =null;
    	
    	if(jArray==null || jArray.length()==0)
    		return null;
    	for(int i=0;i<jArray.length();i++){
    		String rawQuery = "select "
    	}
    	
      }*/
      
      
      public void syncImages(String filename,int id){

    	  InputStream imageis = null;
    	  FileOutputStream fos = null;

    	  imageis = (new HTTPHelper("media/"+filename,_context)).fetch();
    	  InputStream in = new BufferedInputStream(imageis);
    	  ByteArrayOutputStream  data = new ByteArrayOutputStream() ;
    	  int byteRead = 0;
    	  try{
    		  while ((byteRead = in.read())!=-1)
    			  data.write((byte)byteRead);

    		  in.close();
    		  if(id==-1)
    			  fos = _context.openFileOutput("logo.img",Context.MODE_PRIVATE);
    		  else
    			  fos = _context.openFileOutput(Integer.toString(id)+".img",Context.MODE_PRIVATE);
    		  fos.write(data.toByteArray());
    		  fos.flush();
    		  fos.close();
    	  }catch(Exception e){
    		  Log.e(MyRestaurantActivity.TAG, "Error in syncImages: "+e.toString());
    	  }
      }
      
}
