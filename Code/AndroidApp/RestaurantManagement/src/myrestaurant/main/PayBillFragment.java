  // written by: All members
  // tested by: All members
  // debugged by: All members

package myrestaurant.main;

import java.text.DecimalFormat;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;
import android.util.Log;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import myrestaurant.main.R;

public class PayBillFragment extends Fragment {
	
	private TableLayout tlf = null;
	private TableLayout tlc = null;
	private String jsonStr = null;
	private Emrpc rpc = null;
	private EmPrefs emp = null;
	private ProgressBar pbar = null;
	private Button btnPay = null;
	
	@Override
	public void onCreate(Bundle savedInstanceState){
		super.onCreate(savedInstanceState);
	}
	
	@Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

		View v = inflater.inflate(R.layout.paybill,container,false);
		tlf = (TableLayout)v.findViewById(R.id.tableFixed);
		tlc = (TableLayout)v.findViewById(R.id.tableCarte);
		pbar = (ProgressBar)v.findViewById(R.id.pBarOrder);
		btnPay = (Button)v.findViewById(R.id.btnPay);
		btnPay.setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				
				FragmentManager fm = getActivity().getSupportFragmentManager();
		    	FragmentTransaction ft ;
		    	/*if(fm.findFragmentByTag("rightfragment")!=null){
	    			ft = fm.beginTransaction();
	    			ft.setCustomAnimations(android.R.anim.fade_in,
	    	                android.R.anim.fade_out);
	    			ft.remove(fm.findFragmentByTag("rightfragment"));
	    			ft.commit();
	    		}*/
		    	
		        ConfirmDialog f = new ConfirmDialog(getResources().getString(R.string.confirm)+"?",getResources().getString(R.string.yes),
		        		getResources().getString(R.string.notyet),
		    			new View.OnClickListener(){

		    				@Override
		    					public void onClick(View v) {
		    					FragmentManager fm = getActivity().getSupportFragmentManager();
		    			    	FragmentTransaction ft ;
		    			    	Fragment prev = fm.findFragmentByTag("confirmDialog");
		    	            	ft = fm.beginTransaction();
		    	                if (prev != null) {
		    	                    ft.remove(prev);
		    	                    ft.commit();
		    	                }
		    					pbar.setVisibility(View.VISIBLE);
	                            new Thread(new PayBillRun()).start();
		    				}
		    		
		    			},
		    			new View.OnClickListener(){

		    				@Override
		    				public void onClick(View v) {
		    					FragmentManager fm = getActivity().getSupportFragmentManager();
		    			    	FragmentTransaction ft ;
		    			    	Fragment prev = fm.findFragmentByTag("confirmDialog");
		    	            	ft = fm.beginTransaction();
		    	                if (prev != null) {
		    	                    ft.remove(prev);
		    	                    ft.commit();
		    	                }
		    			    	
		    			}
		    		
		    	}); 
		        
            	Fragment prev = fm.findFragmentByTag("confirmDialog");
            	ft = fm.beginTransaction();
                if (prev != null) {
                    ft.remove(prev);
                    ft.commit();
                }
                f.show(ft, "confirmDialog");
			}
		});
		
		new Thread(new OrderItemsRun()).start();
		return v;
	}
	
	
	private Handler itemsHandler = new Handler(){
		public void handleMessage(Message mg){
			TableRow headerf = (TableRow)getActivity().getLayoutInflater().inflate(R.layout.orderheader,null);
			((TextView)headerf.findViewById(R.id.textHeader)).setText("Fixed Price Menus");
			tlf.addView(headerf);
			TableRow headerc = (TableRow)getActivity().getLayoutInflater().inflate(R.layout.orderheader,null);
			((TextView)headerc.findViewById(R.id.textHeader)).setText(R.string.alacarte);
			tlc.addView(headerc);
			
			if(jsonStr!=null){
				JSONObject jsonFixed;
				JSONArray jsonCarte;
				JSONObject jResult;
				try{
					jResult = new JSONObject(jsonStr);
					jsonFixed = jResult.getJSONObject("fixed");
					jsonCarte = jResult.getJSONArray("carte");
					 //Fixed
					TableRow row = (TableRow)getActivity().getLayoutInflater().inflate(R.layout.orderrow,null);
					((TextView)row.findViewById(R.id.textNumber)).setText(jsonFixed.getString("adultsnr"));
					((TextView)row.findViewById(R.id.textLabel)).setText("Adults");
					
					DecimalFormat df = new DecimalFormat("0.00");
					double adultsTot = Double.parseDouble(jsonFixed.getString("adults"));
					((TextView)row.findViewById(R.id.textPrice)).setText(df.format(adultsTot)+" "+emp.getValue("currency"));
					tlf.addView(row);
					
					row = (TableRow)getActivity().getLayoutInflater().inflate(R.layout.orderrow,null);
					((TextView)row.findViewById(R.id.textNumber)).setText(jsonFixed.getString("adultsnr"));
					((TextView)row.findViewById(R.id.textLabel)).setText("Children");
					
					double childrenTot = Double.parseDouble(jsonFixed.getString("children"));
					((TextView)row.findViewById(R.id.textPrice)).setText(df.format(childrenTot)+" "+emp.getValue("currency"));
					tlf.addView(row);
				
					//carte
					
					if(jsonCarte.length()==0){
						row = (TableRow)getActivity().getLayoutInflater().inflate(R.layout.orderrow,null);
						((TextView)row.findViewById(R.id.textLabel)).setText(R.string.noitems);
						tlc.addView(row);

					}
					
					for(int i=0;i<jsonCarte.length();i++){
						JSONObject jObject = jsonCarte.getJSONObject(i); 
						row = (TableRow)getActivity().getLayoutInflater().inflate(R.layout.orderrow,null);
						((TextView)row.findViewById(R.id.textNumber)).setText(jObject.getString("number"));
						((TextView)row.findViewById(R.id.textLabel)).setText(jObject.getString("label"));
						double priceTot = Double.parseDouble(jObject.getString("price"));
						((TextView)row.findViewById(R.id.textPrice)).setText(df.format(priceTot)+" "+emp.getValue("currency"));
						tlc.addView(row);
					}
					
					double total = Double.parseDouble(jResult.getString("total"));
					row = (TableRow)getActivity().getLayoutInflater().inflate(R.layout.orderheader,null);
					((TextView)row.findViewById(R.id.textHeader)).setGravity(Gravity.RIGHT);
					((TextView)row.findViewById(R.id.textHeader)).setText("TOTAL "+df.format(total)+" "+
																					emp.getValue("currency"));
					
					tlc.addView(row);
					
				}catch(JSONException e){
					Log.e(MyRestaurantActivity.TAG,"JSON Exception in getBill: "+e.getMessage());
				}
			}
			
			pbar.setVisibility(View.GONE);
		}
	};
	
	private class OrderItemsRun implements Runnable{

		@Override
		public void run() {
			rpc = new Emrpc(getActivity());
			emp = new EmPrefs(getActivity());
			jsonStr = rpc.getBill(emp.getSid());
			itemsHandler.sendEmptyMessage(0);
		}
		
	}
	
	private Handler payHandler = new Handler(){
		public void handleMessage(Message msg){
			if(msg.what == 0){
				((TextView)getActivity().findViewById(R.id.lblTable)).setText("");
				getActivity().findViewById(R.id.btnMenu).setEnabled(false);
				getActivity().findViewById(R.id.btnDrinks).setEnabled(false);
				getActivity().findViewById(R.id.btnWaiter).setEnabled(false);
				getActivity().findViewById(R.id.btnOrder).setEnabled(false);
				getActivity().findViewById(R.id.btnBill).setEnabled(false);
				getActivity().findViewById(R.id.btnConfig).setVisibility(View.GONE);
				
		        FragmentManager fm = getActivity().getSupportFragmentManager();
		    	FragmentTransaction ft ;
		    	
		    	Fragment f = null;
		    	f = new TableListFragment();
		    	if(fm.findFragmentByTag("rightfragment")!=null){
	    			ft = fm.beginTransaction();
	    			ft.setCustomAnimations(android.R.anim.fade_in,
	    	                android.R.anim.fade_out);
	    			ft.remove(fm.findFragmentByTag("rightfragment"));
	    			ft.commit();
	    		}
		    	ft = fm.beginTransaction();
				ft.setCustomAnimations(android.R.anim.fade_in,
		                android.R.anim.fade_out);
				ft.add(R.id.rightcontent,f,"rightfragment");
				ft.commit();
			}
			//pbar.setVisibility(View.GONE);
			else{
				FragmentManager fm = getActivity().getSupportFragmentManager();
		    	FragmentTransaction ft ;
		    	
		    	ErrorFragment f = new ErrorFragment("Error"); 
            	Fragment prev = fm.findFragmentByTag("errorDialog");
            	ft = fm.beginTransaction();
                if (prev != null) {
                    ft.remove(prev);
                    ft.commit();
                }
                f.show(ft, "errorDialog");
                pbar.setVisibility(View.GONE);
			}
		}
	};
	
	private class PayBillRun implements Runnable{

		@Override
		public void run() {
			boolean result;
			try{
			result = rpc.payBill(emp.getSid());
			}catch(Exception e){
				payHandler.sendEmptyMessage(Integer.parseInt(e.getMessage()));
				return;
			}
			if(result)
				payHandler.sendEmptyMessage(0);
			else
				payHandler.sendEmptyMessage(1);
		}
		
	}

}
