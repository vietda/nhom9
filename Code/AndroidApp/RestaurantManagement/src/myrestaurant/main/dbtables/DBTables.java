  // written by: All members
  // tested by: All members
  // debugged by: All members

package myrestaurant.main.dbtables;

import android.provider.BaseColumns;

public class DBTables {

	DBTables(){
		
	}
	
	public static final class Menus implements BaseColumns{
		
		private Menus(){}
		
		public static final String TAG = "menus";
		
		public static final String ID_MENUS = "_id";
		public static final String LABEL = "label";
		public static final String DESCRIPTION = "description";
		public static final String ID_IMAGE = "idImage";
		public static final String EXTRAS = "extras";
		public static final String PRICE = "price";
		public static final String VISIBLE = "visible";
		public static final String MENU_TYPE = "menuType";
		public static final String FOODBEV_FLAG = "foodbev";
		public static final String MENU_POSITION = "position";
		
		public static final String CREATE_STATEMENT ="CREATE TABLE "+TAG+" ("+ID_MENUS
		+" INTEGER PRIMARY KEY AUTOINCREMENT,"+LABEL+" TEXT,"+DESCRIPTION+" TEXT,"+ID_IMAGE+" INTEGER,"
		+EXTRAS+" TEXT,"+PRICE+" TEXT,"+VISIBLE+" TEXT,"+MENU_TYPE+" TEXT,"+FOODBEV_FLAG+" TEXT,"+MENU_POSITION+" INTEGER);";
		
	}
	
	public static final class Categories implements BaseColumns{
		
		private Categories(){}
		
		public static final String TAG = "categories";
		
		public static final String ID_CATEGORIES = "_id";
		public static final String LABEL = "label";
		public static final String DESCRIPTION = "description";
		public static final String ID_IMAGE = "idImage";
		public static final String EXTRAS = "extras";
		
		public static final String CREATE_STATEMENT = "CREATE TABLE "+TAG+" ("+ID_CATEGORIES
			+" INTEGER PRIMARY KEY AUTOINCREMENT,"+LABEL+" TEXT,"+DESCRIPTION+" TEXT,"+ID_IMAGE+" INTEGER,"
			+EXTRAS+" TEXT);";
		
	}
	
	public static final class Items implements BaseColumns{
		
		private Items(){}
		
		public static final String TAG = "items";

		public static final String ID_ITEMS = "_id";
		public static final String LABEL = "label";
		public static final String DESCRIPTION = "description";
		public static final String ID_IMAGE = "idImage";
		public static final String PRICE = "price";
		public static final String EXTRAS = "extras";
		
		public static final String CREATE_STATEMENT = "CREATE TABLE "+TAG+" ("+ID_ITEMS
			+" INTEGER PRIMARY KEY AUTOINCREMENT,"+LABEL+" TEXT,"+DESCRIPTION+" TEXT,"+ID_IMAGE+" INTEGER,"
			+PRICE+" TEXT,"+EXTRAS+" TEXT);";
	}
	
	public static final class MenuLists implements BaseColumns{
		
		private MenuLists(){}
		
		public static final String TAG = "menulists";

		public static final String ID_MENULISTS = "_id";
		public static final String FK_IDMENUS = "fk_idMenus";
		public static final String FK_IDCATEGORIES = "fk_idCategories";
		public static final String FK_IDITEMS = "fk_idItems";
		public static final String PRICE = "price";
		public static final String CATEGORY_POS = "categoryPosition";
		public static final String ITEM_POS = "itemPosition";
		
		public static final String CREATE_STATEMENT = "CREATE TABLE "+TAG+" ("+ID_MENULISTS
			+" INTEGER PRIMARY KEY AUTOINCREMENT,"+FK_IDMENUS+" INTEGER,"+FK_IDCATEGORIES+" INTEGER,"
			+FK_IDITEMS+" INTEGER,"+PRICE+" TEXT,"+CATEGORY_POS+" INTEGER,"
			+ITEM_POS+" INTEGER);";
		
	}
	
	public static final class Images implements BaseColumns{
		
		private Images(){}
		
		public static final String TAG = "images";
		
		public static final String ID_IMAGES = "_id";
		
		public static final String CREATE_STATEMENT = "CREATE TABLE "+TAG+" ("+ID_IMAGES
			+" INTEGER PRIMARY KEY AUTOINCREMENT)";
		
	}
	
	
	public static final class Config implements BaseColumns{
		
		private Config(){}
		
		public static final String TAG = "config";
		
		public static final String ID_CONFIG = "_id";
		public static final String DEVICE = "device";
		public static final String KEY = "key";
		public static final String VALUE = "value";
		
		public static final String CREATE_STATEMENT = "CREATE TABLE "+TAG+" ("+ID_CONFIG
			+" INTEGER PRIMARY KEY AUTOINCREMENT, "+DEVICE+" TEXT, "+KEY+" TEXT, "+VALUE+" TEXT)";
		
	}
}
