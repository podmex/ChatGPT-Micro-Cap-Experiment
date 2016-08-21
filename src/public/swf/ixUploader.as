package {
	import flash.display.Sprite;
	import flash.display.StageAlign;
	import flash.display.StageScaleMode;
	import flash.events.DataEvent;
	import flash.events.ErrorEvent;
	import flash.events.Event;
	import flash.events.HTTPStatusEvent;
	import flash.events.IOErrorEvent;
	import flash.events.MouseEvent;
	import flash.events.ProgressEvent;
	import flash.events.SecurityErrorEvent;
	import flash.external.ExternalInterface;
	import flash.net.FileFilter;
	import flash.net.FileReference;
	import flash.net.FileReferenceList;
	import flash.net.URLRequest;
	import flash.net.URLRequestMethod;
	import flash.net.URLVariables;
	import flash.net.URLRequestHeader;
	
	public class ixUploader extends Sprite {
		
		private static var DEBUG:Boolean;
		
		private var mUploadUrl:String = 'http://cms.rakhiva.com/media/upload';
		private var mCsrfToken:String = '';
		private var mOnEvent:String;
		
		private var mHit:Sprite;
		private var fr:FileReferenceList;
		
		private var mCurrentIndex:int;
		
		private var mTotalBytes:uint;
		private var mUploadedBytes:uint;
		
		public function ixUploader() {
			DEBUG = loaderInfo.parameters['debug'] || true;
			stage.scaleMode = StageScaleMode.NO_SCALE;
			stage.align = StageAlign.TOP_LEFT;
			
			mHit = new Sprite();
			
			redraw();
			
			addChild(mHit);
			
			mUploadUrl = loaderInfo.parameters['uploadUrl'] || 'http://cms.rakhiva.com/media/upload';
			mOnEvent = loaderInfo.parameters['onEvent'];
			mHit.addEventListener(MouseEvent.MOUSE_DOWN, doBrowse);
			stage.addEventListener(Event.RESIZE, onStageResize);
			
			fr = new FileReferenceList();
			fr.addEventListener(Event.CANCEL, onCancel);
			fr.addEventListener(Event.SELECT, onFilesSelected);
			
			//_progress = new TextField();
			//_progress.x = 10;
			//_progress.y = 10;
			
			
			//addChild(_progress);
			
			dispatch({type: 'flashReady'});
		}
		
		private function onStageResize(e:Event):void {
			dispatch({type: 'debug', data: 'onStageResize(): ' + stage.stageWidth + ',' + stage.stageHeight});
			redraw();
		}
		
		private function redraw():void {
			var ww:Number = stage.stageWidth;
			var hh:Number = stage.stageHeight;
			mHit.graphics.clear();
			mHit.graphics.beginFill(0xffffff, .01);
			mHit.graphics.drawRect(0, 0, ww, hh);
			mHit.graphics.endFill();
			
			if (DEBUG && 0) {
				mHit.graphics.lineStyle(2, 0xff00);
				mHit.graphics.drawRect(1, 1, ww - 2, hh - 2);
				mHit.graphics.moveTo(1, 1);
				mHit.graphics.lineTo(ww - 2, hh - 2);
				mHit.graphics.moveTo(ww - 2, 1);
				mHit.graphics.lineTo(1, hh - 2);
			}
		}
		
		private function doBrowse(e:MouseEvent):void {
			dispatch({type: 'debug', data: 'browse()'});
			fr.browse();
		}
		
		private function onFilesSelected(e:Event):void {
			var flist:Array = [];
			var f:FileReference;
			mTotalBytes = mUploadedBytes = 0;
			dispatch({type: 'debug', data: 'onFilesSelected(): ' + fr.fileList.length + ' files here'});
			for(var i:int = 0; i < fr.fileList.length; ++i) {
				f = fr.fileList[i] as FileReference;
				dispatch({type: 'debug', data: 'file[' + i + ']: ' + f.name + ' (' + f.size + ' b)'});
				flist.push({
					name: f.name,
					size: f.size
				});
				mTotalBytes += f.size;
			}
			
			var ok:Boolean = dispatch({
				type: 'uploadSelect',
				files: flist
			});
			trace('ok ' + (ok ? 'y' : 'n'));
			if(!ok) return;
			mCurrentIndex = -1;
			doUploadNext();
		}
		
		private function doUploadNext():void {
			++mCurrentIndex;
			if(mCurrentIndex == fr.fileList.length) {
				return;
			}
			uploadCurrent();
		}
		
		private function dispatch(o:Object):Object {
			trace(o.type + ' ' + o.data);
			ExternalInterface.call(mOnEvent, o);
			return true;
		}
		
		private function onCancel(e:Event):void {
			dispatch({type: 'debug', data: 'onCancel()'});
			dispatch({
				type: 'uploadCancel'
			});
		}
		
		private function addFileListeners(fr:FileReference):void {
			fr.addEventListener(HTTPStatusEvent.HTTP_STATUS, onHttpStatus);
			fr.addEventListener(IOErrorEvent.IO_ERROR, onError);
			fr.addEventListener(SecurityErrorEvent.SECURITY_ERROR, onError);
			fr.addEventListener(ProgressEvent.PROGRESS, onProgress);
			fr.addEventListener(Event.COMPLETE, onUploadComplete);
			fr.addEventListener(DataEvent.UPLOAD_COMPLETE_DATA, onUploadResponse);
		}
		
		private function removeFileListeners(fr:FileReference):void {
			fr.removeEventListener(HTTPStatusEvent.HTTP_STATUS, onHttpStatus);
			fr.removeEventListener(IOErrorEvent.IO_ERROR, onError);
			fr.removeEventListener(SecurityErrorEvent.SECURITY_ERROR, onError);
			fr.removeEventListener(ProgressEvent.PROGRESS, onProgress);
			fr.removeEventListener(Event.COMPLETE, onUploadComplete);
			fr.removeEventListener(DataEvent.UPLOAD_COMPLETE_DATA, onUploadResponse);
		}
		
		private function uploadCurrent():void {
			var f:FileReference = fr.fileList[mCurrentIndex] as FileReference;
			if(f == null) {
				dispatch({
					type: 'debug',
					data: 'uploadCurrent(): No such file index=' + mCurrentIndex
				});
				return;
			}
			
			var ok:Boolean = dispatch({
				type: 'uploadStart',
				index: mCurrentIndex,
				name: f.name,
				size: f.size
			});
			
			if(ok) {
				addFileListeners(f);
				dispatch({type: 'debug', data: 'upload(' + f.name + ') @ ' + mUploadUrl + ' as "file"'});
				
				var req:URLRequest = new URLRequest(mUploadUrl);
				var vars:URLVariables = new URLVariables();
				vars.item_id = loaderInfo.parameters['item_id'] || 1;
				vars.cat_id = loaderInfo.parameters['cat_id'] || 1;
				var header:URLRequestHeader = new URLRequestHeader("Content-type", "application/octet-stream");
				var csrf:URLRequestHeader = new URLRequestHeader("X-CSRF-Token", mCsrfToken);        
				req.requestHeaders.push(header);
				req.requestHeaders.push(csrf);
				req.method = URLRequestMethod.POST;
				req.data = vars;
				f.upload(req, 'file');
			} else {
				doUploadNext();
			}
		}
		
		private function onProgress(e:ProgressEvent):void {
			var f:FileReference = e.currentTarget as FileReference;
			dispatch({type: 'debug', data: 'onProgress(): ' + f.name});
			dispatch({
				type: 'uploadProgress',
				index: mCurrentIndex,
				bytesTotal: e.bytesTotal,
				bytesUploaded: e.bytesLoaded,
				totalBytes: mTotalBytes,
				totalUploaded: mUploadedBytes + e.bytesLoaded
			});
		}
		
		private function onUploadComplete(e:Event):void {
			var f:FileReference = e.currentTarget as FileReference;
			dispatch({type: 'debug', data: 'onUploadComplete(): ' + f.name});
			dispatch({
				type: 'uploadComplete',
				index: mCurrentIndex
			});
			mUploadedBytes += f.size;
		}
		
		private function onUploadResponse(e:DataEvent):void {
			dispatch({
				type: 'uploadResponse',
				index: mCurrentIndex,
				data: e.data
			});
			var f:FileReference = fr.fileList[mCurrentIndex] as FileReference;
			dispatch({type: 'debug', data: 'onUploadResponse(' + f.name + '): ' + e.data});
			removeFileListeners(f);
			doUploadNext();
		}
		
		private function onHttpStatus(e:HTTPStatusEvent):void {
			var f:FileReference = fr.fileList[mCurrentIndex] as FileReference;
			dispatch({type: 'debug', data: 'onHttpStatus(' + f.name + '): ' + e.status});
			dispatch({
				type: 'uploadHTTPStatus',
				index: mCurrentIndex,
				data: e.status
			});
		}
		
		private function onError(e:ErrorEvent):void {
			var f:FileReference = fr.fileList[mCurrentIndex] as FileReference;
			dispatch({type: 'debug', data: 'onError(' + f.name + '): ' + e.toString()});
			dispatch({
				type: 'uploadComplete',
				index: mCurrentIndex,
				data: e.toString()
			});
			removeFileListeners(f);
			doUploadNext();
		}
	}
}