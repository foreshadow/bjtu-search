import sys
import tornado.ioloop
import tornado.web
sys.path.append('../jieba')
import jieba
import jieba.analyse
import json

class MainHandler(tornado.web.RequestHandler):
    def get(self):
        content = self.get_argument('content')
        tags = jieba.analyse.extract_tags(content)
        self.write(json.dumps(tags))
        
application = tornado.web.Application([(r"/", MainHandler),])

if __name__ == "__main__":
    application.listen(9228)
    tornado.ioloop.IOLoop.instance().start()
