  // written by: All members
  // tested by: All members
  // debugged by: All members

package myrestaurant.main;

import java.io.FileNotFoundException;
import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Handler;
import android.os.Message;
import android.os.Parcelable;
import android.support.v4.app.DialogFragment;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.view.PagerAdapter;
import android.support.v4.view.ViewPager;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.AdapterView.OnItemClickListener;
import myrestaurant.main.R;
import myrestaurant.main.dbtables.DBRowItem;
import myrestaurant.main.dbtables.DBRowMenus;

public class MenuPagerAdapter extends PagerAdapter 
{
    private ArrayList<DBRowMenus> menuTitles = null; 
    
    private ArrayList<ItemAdapter> ItemAdaptersList = null;
    
    private final Context context;
    boolean isDrinks ;
    LayoutInflater li;
    private EmPrefs emp = null;
    
    public MenuPagerAdapter( Context context,boolean isDrinks )
    {
        this.context = context;
        
        ItemAdaptersList = new ArrayList<ItemAdapter>();
        
        li = LayoutInflater.from(context);
        DBContentProvider dbc = new DBContentProvider(context);
        this.isDrinks = isDrinks;
        menuTitles = dbc.getMenuTitles(isDrinks); 
        dbc.close();
        emp = new EmPrefs(context);
    }
 
    @Override
    public int getCount()
    {
        return menuTitles.size();
    }
 
    @Override
    public Object instantiateItem( View pager, int position )
    {
    	if (emp.getValue("displaymode").equals(new String("gridview"))){
    		return instantiateItemGrid(pager,position);
    	}else{
    		return instantiateItemList(pager,position);
    	}
    }
    
    private Object instantiateItemList( View pager, int position )
    {
    	View v = li.inflate(R.layout.menupager1, null);
		ListView listview = (ListView) v.findViewById(R.id.list);
    	
    	TextView tv = (TextView)v.findViewById(R.id.menuTitle);
    	tv.setText( menuTitles.get(position).getLabel());
    	
    	ItemAdapter adapter = new ItemAdapter(context,position);
    	ItemAdaptersList.add(position, adapter);
    	listview.setAdapter(adapter);
    	
        listview.setOnItemClickListener(new OnItemClickListener() {
            public void onItemClick(AdapterView<?> parent, View v, int position, long id) {
                //Toast.makeText(context, "" + position, Toast.LENGTH_SHORT).show();
            	FragmentManager fm = ((FragmentActivity)context).getSupportFragmentManager();
            	FragmentTransaction ft = fm.beginTransaction();
            	DialogFragment f = new ItemFragment((DBRowItem)parent.getAdapter().getItem(position)); 
            	Fragment prev = fm.findFragmentByTag("itemDialog");
                if (prev != null) {
                    ft.remove(prev);
                    ft.commit();
                }
                f.show(ft, "itemDialog");
            }
        });
        ((ViewPager)pager).addView( v, 0 );
        return v;
    }
    
    private Object instantiateItemGrid( View pager, int position )
    {
    	View v = li.inflate(R.layout.menupager, null);
    	GridView listview = (GridView) v.findViewById(R.id.gridview);
    	TextView tv = (TextView)v.findViewById(R.id.menuTitle);
    	tv.setText( menuTitles.get(position).getLabel());
    	
    	ItemAdapter adapter = new ItemAdapter(context,position);
    	ItemAdaptersList.add(position, adapter);
    	listview.setAdapter(adapter);
    	
        listview.setOnItemClickListener(new OnItemClickListener() {
            public void onItemClick(AdapterView<?> parent, View v, int position, long id) {
                //Toast.makeText(context, "" + position, Toast.LENGTH_SHORT).show();
            	FragmentManager fm = ((FragmentActivity)context).getSupportFragmentManager();
            	FragmentTransaction ft = fm.beginTransaction();
            	DialogFragment f = new ItemFragment((DBRowItem)parent.getAdapter().getItem(position)); 
            	Fragment prev = fm.findFragmentByTag("itemDialog");
                if (prev != null) {
                    ft.remove(prev);
                    ft.commit();
                }
                f.show(ft, "itemDialog");
            }
        });
        ((ViewPager)pager).addView( v, 0 );
        return v;
    }
 
    @Override
    public void destroyItem( View pager, int position, Object view )
    {
    	unbindDrawables(pager);
        ((ViewPager)pager).removeView( (View)view );
    }
 
    @Override
    public boolean isViewFromObject( View view, Object object )
    {
        return view.equals( object );
    }
 
    @Override
    public void finishUpdate( View view ) {}
 
    @Override
    public void restoreState( Parcelable p, ClassLoader c ) {}
 
    @Override
    public Parcelable saveState() {
        return null;
    }
 
    @Override
    public void startUpdate( View view ) {}
    
    private void unbindDrawables(View view) {
        if (view.getBackground() != null) {
            view.getBackground().setCallback(null);
        }
        if (view instanceof ViewGroup) {
            for (int i = 0; i < ((ViewGroup) view).getChildCount(); i++) {
                unbindDrawables(((ViewGroup) view).getChildAt(i));
            }
            //((ViewGroup) view).removeAllViews();
        }
    }
    
    
    /*
     * ITEM ADAPTER FOR GRIDVIEW/LISTVIEW
     * 
     */
    
    private class ItemAdapter extends BaseAdapter {
        private Context mContext;
        private int pagePos=0;
        
        private ArrayList<DBRowItem> itemsList = null;
        private Map<Integer,Boolean> categoriesList = null;
        
        public ItemAdapter(Context c,int pos) {
            mContext = c;
            pagePos = pos;
            
        }
        
        public void setItemsList(ArrayList<DBRowItem> arg0){
        	itemsList = arg0;
        	String tmpCat = null;
        	String currCat = null;
        	categoriesList = new HashMap<Integer,Boolean>();
        	for(int i=0; i < itemsList.size() ; i++){
        		currCat = itemsList.get(i).getCategory();
        		if(i==0 || !tmpCat.contentEquals(currCat)){
        			tmpCat = currCat;
        			categoriesList.put(new Integer(i), new Boolean(true));
        		}	
        	}
        	
        	System.out.println("########## "+categoriesList.keySet());
        }
        
        public int getCount() {
        	if (itemsList==null)
        		return 1;
        	else
        		return itemsList.size();
        }

        public Object getItem(int position) {
        		if(itemsList!=null)
        			return itemsList.get(position);
        		else
        			return null;
        }

        public long getItemId(int position) {
            return 0;
        }

        // create a new ImageView for each item referenced by the Adapter
        public View getView(int position, View convertView, ViewGroup parent) {
        	
        	boolean isGrid = emp.getValue("displaymode").equals(new String("gridview"))?true:false;
        	ViewHolder holder;
        	
        	if(itemsList==null){
        		if (convertView == null){
        			if(isGrid)
        				((GridView)parent).setNumColumns(1);
        			View v = li.inflate(R.layout.spinner, null);
        			holder = new ViewHolder();
        			holder.isSpinner=true;
        			v.setTag(holder);
        			new Thread(new LoadItemsRun(context,pagePos,menuTitles.get(pagePos).getId())).start();
        			return v;
        		}
        		else
        			return  convertView;
        	}
        	else{
        		if(isGrid)
        			((GridView)parent).setNumColumns(GridView.AUTO_FIT);
        		View v = null;
        		if (convertView == null || ((ViewHolder)convertView.getTag()).isSpinner  ) { 
        			if(isGrid)
        				v = li.inflate(R.layout.itemview, null);
        			else{
        				v = li.inflate(R.layout.itemview1head, null);
        			}
        			holder  = new ViewHolder();
        			holder.label = (TextView)v.findViewById(R.id.itemTitle);
        			holder.description = (TextView)v.findViewById(R.id.itemDescription);
        			holder.category = (TextView)v.findViewById(R.id.itemCategory);
        			holder.price = (TextView)v.findViewById(R.id.itemPrice);
        			holder.image = (ImageView)v.findViewById(R.id.itemImage);
        			holder.isSpinner = false;
        			v.setTag(holder);
        			
        		} else {
        			holder = (ViewHolder) convertView.getTag();
        			v = convertView;
        		}
        		Bitmap bm = null;
        		try {
    				BitmapFactory.Options options = new BitmapFactory.Options();
    				options.inTempStorage = new byte[16*1024];
    				options.inSampleSize = 4;
    				bm =  BitmapFactory.decodeStream(
							mContext.openFileInput(Integer.toString(itemsList.get(position).getIdImage())+".img"),null,options);
					
				} catch (FileNotFoundException e) {
					Log.e(MyRestaurantActivity.TAG,"Error image file not found: "+e.getMessage());
				}
				holder.image.setScaleType(ImageView.ScaleType.FIT_CENTER);
				holder.image.setImageBitmap(bm);
				holder.label.setText(itemsList.get(position).getLabel());
				holder.category.setText(itemsList.get(position).getCategory());
        		if(!isGrid){
        			holder.description.setText(itemsList.get(position).getDescription());
        			if(itemsList.get(position).getMenuType().contentEquals("c")){
        				String price = itemsList.get(position).getPrice();
        				int iPrice = (int)(Double.parseDouble(price)*100);
        				double tot = iPrice/100.0;
        				
        				DecimalFormat df = new DecimalFormat("0.00");
        				holder.price.setText(df.format(tot)+" "+emp.getValue("currency"));
        			}
        			if(categoriesList.containsKey(Integer.valueOf(position) ))
        				holder.category.setVisibility(View.VISIBLE);
        			else
        				holder.category.setVisibility(View.GONE);
        		}
        		return v;
        	}
        }
        
        private class ViewHolder {
        	TextView label;
        	TextView description;
        	TextView price;
        	TextView category;
        	ImageView image;
        	boolean isSpinner = false;
        }
    }
    
    final Handler LoadItemsHandler = new Handler() {
    	  
        public void handleMessage(Message msg) {
        	
        	ItemAdaptersList.get(msg.what).notifyDataSetChanged();
           
        };
    };
    
    // RUNNABLE
    private class LoadItemsRun implements Runnable{
    	
    	Context context = null;
    	int id = 0;
    	int position = 0;
    	
    	LoadItemsRun(Context c,int position,int id){
    		this.position=position;
    		this.context = c;
    		this.id = id;
    	}
    	
		@Override
		public void run() {
			try {
				Thread.sleep(300);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				//e.printStackTrace();
			}
			DBContentProvider dbc = new DBContentProvider(context);
			ItemAdaptersList.get(position).setItemsList(dbc.getItemsList(id));
            dbc.close();
            LoadItemsHandler.sendEmptyMessage(position);
		}
    	
    }
}
