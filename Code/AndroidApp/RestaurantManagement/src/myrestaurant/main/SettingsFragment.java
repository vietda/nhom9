  // written by: All members
  // tested by: All members
  // debugged by: All members

package myrestaurant.main;

import android.content.Context;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager.NameNotFoundException;
import android.net.wifi.WifiInfo;
import android.net.wifi.WifiManager;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.RadioGroup;
import myrestaurant.main.R;
import myrestaurant.main.dbtables.DBSyncManager;

public class SettingsFragment extends Fragment {
	
	LayoutInflater li = null;
	EmPrefs emp = null;
	Button btnSync = null;
	Button btnSave = null;
	Button btnClose = null;
	
	EditText etServer = null;
	EditText etPassword = null;
	
	RadioGroup rg = null;
	ProgressBar pbar = null;
	
	@Override
	public void onCreate(Bundle savedInstanceState){
		super.onCreate(savedInstanceState);
		li = getActivity().getLayoutInflater();
		emp = new EmPrefs(getActivity());

	}
	
	@Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

		View v = inflater.inflate(R.layout.settings,container,false);
		
		String strVersion ="";
		  
		PackageInfo packageInfo;
		try {
			packageInfo = getActivity().getPackageManager().getPackageInfo(getActivity().getPackageName(), 0);
			strVersion = packageInfo.versionName ;
		} catch (NameNotFoundException e) {
			//e.printStackTrace();
			strVersion = "Cannot load Version!";
		}
		
		WifiManager wifiManager = (WifiManager)getActivity().getSystemService(Context.WIFI_SERVICE);
        WifiInfo wifiInfo = wifiManager.getConnectionInfo();
        String macAddress = wifiInfo == null ? "" : wifiInfo.getMacAddress();
		
        ((EditText)v.findViewById(R.id.txtVersion)).setText(strVersion);
        ((EditText)v.findViewById(R.id.txtMac)).setText(macAddress);
        (etServer = (EditText)v.findViewById(R.id.txtServer)).setText(emp.getValue("server"));
        (etPassword = (EditText)v.findViewById(R.id.txtPassword)).setText(emp.getValue("password"));
        
        rg = (RadioGroup)v.findViewById(R.id.rgSync);
        pbar = (ProgressBar)v.findViewById(R.id.pBarSync);
        
        btnSync = (Button)v.findViewById(R.id.btnSync);
        btnSave = (Button)v.findViewById(R.id.btnConfSave);
        btnClose = (Button)v.findViewById(R.id.btnConfClose);
        
        
        btnSave.setOnClickListener(new View.OnClickListener(){
        	@Override
			public void onClick(View v) {
        		emp.setKeyValue("server", etServer.getText().toString());
        		emp.setKeyValue("password", etPassword.getText().toString());
        		emp.commit();
        		
        		/*FragmentManager fm = getActivity().getSupportFragmentManager();
            	FragmentTransaction ft ;
            	Fragment f = null;
            	if(fm.findFragmentByTag("rightfragment")!=null){
	    			ft = fm.beginTransaction();
	    			ft.setCustomAnimations(android.R.anim.fade_in,
		                    android.R.anim.fade_out);
	    			ft.remove(fm.findFragmentByTag("rightfragment"));
	    			ft.commit();
	    		}
            	f = new TableListFragment();
            	ft = fm.beginTransaction();
        		ft.setCustomAnimations(android.R.anim.fade_in,
                        android.R.anim.fade_out);
        		ft.add(R.id.rightcontent,f,"rightfragment");
        		ft.commit();
        		getActivity().findViewById(R.id.btnConfig).setVisibility(View.GONE);
        	*/}
        });
        
        
        btnClose.setOnClickListener(new View.OnClickListener(){
        	@Override
			public void onClick(View v) {
        		/*emp.setKeyValue("server", etServer.getText().toString());
        		emp.setKeyValue("password", etPassword.getText().toString());
        		emp.commit();
        		*/
        		FragmentManager fm = getActivity().getSupportFragmentManager();
            	FragmentTransaction ft ;
            	Fragment f = null;
            	if(fm.findFragmentByTag("rightfragment")!=null){
	    			ft = fm.beginTransaction();
	    			ft.setCustomAnimations(android.R.anim.fade_in,
		                    android.R.anim.fade_out);
	    			ft.remove(fm.findFragmentByTag("rightfragment"));
	    			ft.commit();
	    		}
            	f = new TableListFragment();
            	ft = fm.beginTransaction();
        		ft.setCustomAnimations(android.R.anim.fade_in,
                        android.R.anim.fade_out);
        		ft.add(R.id.rightcontent,f,"rightfragment");
        		ft.commit();
        		getActivity().findViewById(R.id.btnConfig).setVisibility(View.GONE);
        	}
        });
        
        btnSync.setOnClickListener(new View.OnClickListener(){
        	@Override
			public void onClick(View v) {
        		int rbid = rg.getCheckedRadioButtonId();
        		pbar.setVisibility(View.VISIBLE);
        		new Thread(new syncRun(rbid)).start();
        	}
        });
        
		return v;
	}

	private final Handler syncHandler = new Handler(){
	        public void handleMessage(Message msg) {
	        	pbar.setVisibility(View.GONE);
	        }
	}; 
	
	private class syncRun implements Runnable{
		
		private int rbid = 0;
		
		syncRun(int rbid){
			this.rbid = rbid;
		}
		
		@Override
		public void run() {
			
			DBSyncManager dbs = new DBSyncManager(getActivity());               
    		if(rbid == R.id.radio0){
    			dbs.syncConfig();
    		}else if(rbid == R.id.radio1){
    			dbs.syncConfig();
    			dbs.syncData();
    		}else if(rbid == R.id.radio2){
    			dbs.syncFullMenu();
    		}
    		dbs.close();
    		syncHandler.sendEmptyMessage(0);
		}
		
	}
	
}
